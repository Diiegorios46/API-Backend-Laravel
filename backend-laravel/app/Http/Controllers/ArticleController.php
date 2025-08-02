<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function save(Request $request){
        // sacar los datos de usuario identificado 
        $identify = $request->user;

        // recoger los datos enviados en la peticion
        $title = $request->input("title" , null);
        $content = $request->input("content", null);

        // preparar el articulo a guardar
        $articleToSave = [
            "title" => $title,
            "content" => $content,
            "user_id" => $identify->sub
        ];

        // validar los datos
        $validator = Validator::make($articleToSave, [
            "title" => "required" ,
            "content" => "required" ,
            "user_id" => "required"
        ]);

        // verificar si esta correcta la validacion 
        if($validator->fails()){

            $data = [
                "status" => "error",
                "message" => "los datos no son validos" , 
                "error" => $validator->errors()
            ];

        }else{

            $article = new Article();

            $article->title = $title;
            $article->content = $content;
            $article->user_id = $identify->sub;

            $article->save();

            $data = [
                "status" => "sucess",
                "message" => "Articulo guardado correctamente",
                "article" => $article
            ];

        }

        // guardo
        return response()->json($data);
    }

    public function articles(Request $request, $page = 1){

        $itemsPerPage = 5 ; 

        $articles = Article::orderBy("created_at", "desc")->paginate($itemsPerPage , ["*"] , "page" , $page);

        return response()->json([
            "status" => "success", 
            "page" => $articles->currentPage(),
            "pages" => $articles->lastPage(),
            "itemsPerPage" => $itemsPerPage,
            "totalArticles" => $articles->total(),
            "articles" => $articles->items() 
        ]);
    }

    public function article(Request $request , $id){
        $article = Article::with("user")->find($id);

        if(empty($article)){
            return response()->json([
                "status" => "error" ,
                "message" => "No existe el articulo"
            ]);
        }else{
            return response()->json([
                "status" => "success",
                "article" => $article
            ]);
        }
    }

    public function articleByUser(Request $request , $user_id){

        $articles = Article::where("user_id" , $user_id)->orderBy("created_at" , "desc")->get();

        if($articles->isEmpty()){
            return response()->json([
                "status" => "error",
                "message" => "No hay articulos para mostrar"
            ]);
        }else{
            return response()->json([
                "status" => "success",
                "article" => $articles
            ]);
        }
    }

    public function search($searchString){
        $articles = Article::where("title" , "LIKE", "%$searchString%")
                   ->orWhere("content", "LIKE", "%$searchString%")
                   ->orderBy("created_at" , "desc")
                   ->get();

        if(empty($articles)){
            return response()->json([
                "status" => "error" ,
                "message" => "No existe el articulo"
            ]);
        }else{
            return response()->json([
                "status" => "success",
                "article" => $articles
            ]);
        }

    }

    public function delete(Request $request , $id){

        try {
            $article = Article::find($id);

            if (!empty($article)) {

                $article->delete();

                return response()->json([
                    "status" => "success",
                    "message" => $article
                ]);
                
            }else{
                return response()->json([
                    "status" => "error",
                    "message" => "No hay artículo para borrar"
                ]);
            }



        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Error al borrar el artículo",
                "error" => $e->getMessage()
            ]);
        }

    }

    public function update(Request $request , $id){

        $identify = $request->user;

        $article = Article::find($id);

        $title = $request->input("title" , $article->title);
        $content = $request->input("content" , $article->content);
        
        $articleToUpdate = [
            "title" => $title,
            "content" => $content
        ];

        $validator = Validator::make($articleToUpdate , [
            "title" => "required" , 
            "content" => "required",
            "user_id" => $identify->sub
        ]);

        if($validator->fails()){
            $data = [
                "status" => "error" ,
                "message" => "Los datos no se validaron bien",
                "error" => $validator->errors()
            ];
        }else{  

            $articleUpdated = $article->update($articleToUpdate);

            $data = [
                "status" => "success" ,
                "update" => $articleUpdated , 
                "articleToUpdate" => $articleToUpdate , 

            ];
        }

        return response()->json($data); 

    }
    

    public function upload(Request $request, $id)
    {
        // Validar que se haya enviado una imagen válida
        $validator = Validator::make($request->all(), [
            "file0" => "required|image|mimes:jpg,jpeg,gif,png"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "message" => "Imagen inválida",
                "errors" => $validator->errors()
            ]);
        }

        // Obtener imagen y artículo
        $image = $request->file("file0");
        $article = Article::find($id);

        if (!$article) {
            return response()->json([
                "status" => "error",
                "message" => "Artículo no encontrado"
            ]);
        }

        // Generar nuevo nombre de imagen
        $image_name = time() . $image->getClientOriginalName();

        // Guardar imagen nueva en disco
        \Storage::disk("posters")->put($image_name, \File::get($image));

        // Borrar imagen anterior si existe
        if ($article->image && \Storage::disk("posters")->exists($article->image)) {
            \Storage::disk("posters")->delete($article->image);
        }

        // Asociar nueva imagen al artículo
        $article->image = $image_name;
        $article->save();

        return response()->json([
            "status" => "success",
            "article" => $article
        ]);

    }


    public function poster($file){
        $disk = \Storage::disk("posters");

        if($disk->exists($file)){
            $path = $disk->path($file);
            return response()->file($path);

        }else{
            return response()->json([
                "status" => "error" ,
                "message" => "imagen no encontrada"
            ]);
        }
    }
}

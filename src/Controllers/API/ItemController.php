<?php

namespace Controllers\API;

use App\HandleForm;
use App\Middleware;
use Models\Items;

class ItemController
{
    /**
     * CREATE
     *
     * @return void
     */
    public function create(): void
    {
//        no time to this
//        if (is_null(Middleware::init(__METHOD__))) {
//            http_response_code(403);
//            echo json_encode(['message' => 'Authorization failed!']);
//            exit();
//        }

        $request = json_decode(file_get_contents('php://input'));

        $output = HandleForm::validations([
            [$request->name, 'required', 'Please enter a title for the item!'],
            [$request->size, 'required', 'Please enter a size for the item!'],
            [$request->price, 'integer', 'Please enter a price for the item!'],
            [$request->currencyId, 'required', 'Please enter a currencyId for the item!'],
            [$request->typeId, 'required', 'Please enter a typeId of item!'],
            [$request->model, 'required', 'Please enter a model of item!'],
        ]);

        if ($output['status'] != 'OK') {
            http_response_code(422);
            echo json_encode($output);
        } elseif (Items::create($request)) {
            http_response_code(201);
            echo json_encode(['message' => 'Data saved successfully!']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Failed during saving data!']);
        }
    }

//
//    /**
//     * UPDATE
//     *
//     * @return void
//     */
//    public function update(): void
//    {
//        if (is_null(Middleware::init(__METHOD__))) {
//            http_response_code(403);
//            echo json_encode(['message' => 'Authorization failed!']);
//            exit();
//        }
//
//        $request = json_decode(file_get_contents('php://input'));
//
//        $output = HandleForm::validations([
//            [$request->title, 'required', 'Please enter a title for the post!'],
//            [$request->subtitle, 'required', 'Please enter a subtitle for the post!'],
//            [$request->body, 'required', 'Please enter a body for the post!'],
//        ]);
//
//        if ($output['status'] != 'OK') {
//            http_response_code(422);
//            echo json_encode($output);
//        } elseif (Blog::update($request)) {
//            Database::query("SELECT * FROM posts WHERE id = :id");
//            Database::bind(':id', $request->id);
//
//            $currentPost = Database::fetch();
//
//            if (isset($_FILES['image']['type'])) {
//                HandleForm::upload($_FILES['image'], ['jpeg', 'jpg', 'png'], 5000000, '../public/assets/images/', 85, substr($currentPost['slug'], 0, -11));
//            }
//
//            XmlGenerator::feed();
//            Cache::clearCache('blog.show.' . $currentPost['slug']);
//            Cache::clearCache(['index', 'blog.index', 'api.index']);
//
//            http_response_code(200);
//            echo json_encode(['message' => 'Data updated successfully!']);
//        } else {
//            http_response_code(404);
//            echo json_encode(['message' => 'Failed during updating data!']);
//        }
//    }
}
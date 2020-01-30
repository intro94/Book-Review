<?php

namespace App\Http\Controllers;

use App\Book;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psy\Util\Json;

class CommentController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function commentCreate(Request $request): string
    {
        $answer = [
            'error' => false,
            'message' => '',
            'redirect' => ''
        ];

        try {
            if (Auth::guest())
                throw new \Exception(__('Access denied'));

            $comment_data = $request->all();

            $book = Book::findOrFail($comment_data['book_id']);

            Comment::create([
                'user_id' => Auth::user()->user->id,
                'book_id' => $book->id,
                'comment' => $comment_data['comment_text'],
                'parent_comment' => $comment_data['parent_id'],
            ]);
        } catch (\Exception $e) {
            $answer['error'] = true;
            $answer['message'] = $e->getMessage();
        }

        return Json::encode($answer);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function commentDelete(Request $request): string
    {
        $answer = [
            'error' => false,
            'message' => '',
            'redirect' => ''
        ];

        try {
            $comment_id = $request->input('comment_id');

            $comment = Comment::findOrFail($comment_id);

            if (Auth::guest() || $comment->user_id != Auth::user()->user->id) {
                throw new \Exception(__('Access denied'));
            } else {
                Comment::where('parent_comment', $comment->id)->update(['parent_comment' => $comment->parent_comment]);
                $comment->delete();
            }
        } catch (\Exception $e) {
            $answer['error'] = true;
            $answer['message'] = $e->getMessage();
        }

        return Json::encode($answer);
    }
}

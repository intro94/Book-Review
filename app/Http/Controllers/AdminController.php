<?php

namespace App\Http\Controllers;

use App\Author;
use App\Book;
use App\Comment;
use App\EntryPoint;
use App\User;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Psy\Util\Json;

/**
 * Class AdminController
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    /**
     * @param int $id
     * @return Factory|View
     */
    public function authorInfo(int $id)
    {
        return view('admin.author-info', ['author' => Author::findOrFail($id)]);
    }

    /**
     * @param int $id
     * @return Factory|View
     */
    public function bookInfo(int $id)
    {
        $book = Book::findOrFail($id);
        $view_data = [
            'book' => $book,
            'comments_tree' => Comment::getCommentsTree($book->id)
        ];

        return view('admin.book-info', $view_data);
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
            Comment::where('parent_comment', $comment->id)->update(['parent_comment' => $comment->parent_comment]);
            $comment->delete();
        } catch (Exception $e) {
            $answer['error'] = true;
            $answer['message'] = $e->getMessage();
        }

        return Json::encode($answer);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function authorDelete(Request $request): string
    {
        $answer = [
            'error' => false,
            'message' => '',
            'redirect' => route('admin.author.list')
        ];

        try {
            $author_id = $request->input('author_id');
            Author::destroy($author_id);
        } catch (Exception $e) {
            $answer['error'] = true;
            $answer['message'] = $e->getMessage();
        }

        return Json::encode($answer);
        //не понимаю почему возвращается ни один из наследников Response
    }

    /**
     * @param Request $request
     * @return string
     */
    public function bookDelete(Request $request): string
    {
        $answer = [
            'error' => false,
            'message' => '',
            'redirect' => route('admin.book.list')
        ];

        try {
            $book_id = $request->input('book_id');
            Book::destroy($book_id);
        } catch (Exception $e) {
            $answer['error'] = true;
            $answer['message'] = $e->getMessage();
        }

        return Json::encode($answer);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function authorUpdate(Request $request): string
    {
        $answer = [
            'error' => false,
            'message' => '',
            'redirect' => ''
        ];

        try {
            $validated_data = $request->validate([
                'author_id' => 'required|integer',
                'last_name' => 'required|max:60|string',
                'first_name' => 'required|max:60|string',
                'birthday' => 'required|date',
            ]);

            $author = Author::findOrFail($validated_data['author_id']);

            $author->last_name = $validated_data['last_name'];
            $author->first_name = $validated_data['first_name'];
            $author->birth_date = $validated_data['birthday'];

            $author->save();
        } catch (Exception $e) {
            $answer['error'] = true;
            $answer['message'] = $e->getMessage();
        }

        return Json::encode($answer);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function bookUpdate(Request $request): string
    {
        $answer = [
            'error' => false,
            'message' => '',
            'redirect' => ''
        ];

        try {
            $validated_data = $request->validate([
                'book_id' => 'required|integer',
                'author_id' => 'required|integer',
                'title' => 'required|max:255|string',
                'description' => 'required|max:1000|string',
            ]);

            $book = Book::findOrFail($validated_data['book_id']);
            $author = Author::findOrFail($validated_data['author_id']);

            $book->author_id = $author->id;
            $book->title = $validated_data['title'];
            $book->description = $validated_data['description'];

            $book->save();
        } catch (Exception $e) {
            $answer['error'] = true;
            $answer['message'] = $e->getMessage();
        }

        return Json::encode($answer);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function authorCreate(Request $request): string
    {
        $answer = [
            'error' => false,
            'message' => '',
            'redirect' => ''
        ];

        try {
            $validated_data = $request->validate([
                'last_name' => 'required|max:60|string',
                'first_name' => 'required|max:60|string',
                'birthday' => 'required|date',
            ]);

            $author = Author::create([
                'last_name' => $validated_data['last_name'],
                'first_name' => $validated_data['first_name'],
                'birth_date' => $validated_data['birthday'],
            ]);

            $answer['redirect'] = route('admin.author.info', $author->id);
        } catch (Exception $e) {
            $answer['error'] = true;
            $answer['message'] = $e->getMessage();
        }

        return Json::encode($answer);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function bookCreate(Request $request): string
    {
        $answer = [
            'error' => false,
            'message' => '',
            'redirect' => ''
        ];

        try {
            $validated_data = $request->validate([
                'author_id' => 'required|integer',
                'title' => 'required|max:255|string',
                'description' => 'required|max:1000|string',
            ]);

            $author = Author::findOrFail($validated_data['author_id']);

            $book = Book::create([
                'author_id' => $author->id,
                'title' => $validated_data['title'],
                'description' => $validated_data['description'],
            ]);

            $answer['redirect'] = route('admin.book.info', $book->id);
        } catch (Exception $e) {
            $answer['error'] = true;
            $answer['message'] = $e->getMessage();
        }

        return Json::encode($answer);
    }

    /**
     * @param int $id
     * @return Factory|View
     */
    public function userInfo(int $id)
    {
        return view('admin.user-info', ['user' => User::findOrFail($id)]);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function userUpdate(Request $request): string
    {
        $answer = [
            'error' => false,
            'message' => '',
            'redirect' => ''
        ];

        try {
            $validated_data = $request->validate([
                'user_id' => 'required|integer',
                'last_name' => 'required|max:60|string',
                'first_name' => 'required|max:60|string',
                'email' => 'required|string|email|max:255',
                'birthday' => 'date',
            ]);

            $user = User::findOrFail($validated_data['user_id']);

            $email_verify = User::where('email', $validated_data['email'])->first();

            if ($email_verify && $email_verify->id != $user->id)
                throw new Exception(__('E-mail is busy'));

            $user->update([
                'last_name' => $validated_data['last_name'],
                'first_name' => $validated_data['first_name'],
                'email' => $validated_data['email'],
                'birth_date' => $validated_data['birthday'],
            ]);
        } catch (Exception $e) {
            $answer['error'] = true;
            $answer['message'] = $e->getMessage();
        }

        return Json::encode($answer);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function unlinkSocial(Request $request)
    {
        $answer = [
            'error' => false,
            'message' => '',
            'redirect' => ''
        ];

        try {
            $validated_data = $request->validate([
                'type' => 'required|integer',
                'user_id' => 'required|integer',
            ]);

            $user = User::findOrFail($validated_data['user_id']);

            if ($user->entryPoints->where('type', '!=', EntryPoint::NATIVE_REG)->count() <= 1)
                throw new Exception(__('User must be at least one linked social.'));

            if ($user->entryPoints->where('type', $validated_data['type'])->count() < 1)
                throw new Exception(__('The given data was invalid.'));

            $user->entryPoints->where('type', $validated_data['type'])->first()->delete();
        } catch (Exception $e) {
            $answer['error'] = true;
            $answer['message'] = $e->getMessage();
        }

        return Json::encode($answer);
    }
}

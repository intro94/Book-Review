<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Comment
 * @package App
 */
class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'book_id', 'comment', 'parent_comment',
        //user_id - лучше не делать заполняемым, основываться на связи между моделями
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * @param int $book_id
     * @param int $parent_comment
     * @param int $depth
     * @return array
     */
    public static function getCommentsTree(int $book_id, int $parent_comment = 0, int $depth = 0): array
    //шикардос... имея 100 коментов - будет минимум 100 запросов
    {
        $commentaries = Comment::all()->where('book_id', $book_id)->where('parent_comment', $parent_comment)->sortBy('id');

        //camelCase!
        $comment_tree = [];

        foreach ($commentaries as $comment) {
            // и нафига тебе stdClass ?
            $comment_tree[] = (object)[
                'self' => $comment,
                'depth' => $depth,
                'children' => ($depth < 2) ? self::getCommentsTree($book_id, $comment->id, $depth + 1) : []
                //а тут получаем sql query в рекурсии?
            ];
        }

        return $comment_tree;
    }
}

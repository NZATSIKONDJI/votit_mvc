<?php
namespace App\Repository;

use App\Db\Mysql;
use App\Entity\Comment;
use PDO;

class CommentRepository {
    public function __construct() {}

    public function findByPollId(int $poll_id): array {
        $stmt = Mysql::getInstance()->getPdo()->prepare('SELECT * FROM comment WHERE poll_id = ? ORDER BY created_at DESC');
        $stmt->execute([$poll_id]);
        $comments = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comments[] = new Comment(
                $data['id'],
                $data['content'],
                $data['user_id'],
                $data['poll_id'],
                $data['created_at']
            );
        }
        return $comments;
    }

    public function create(Comment $comment): Comment {
        $stmt = Mysql::getInstance()->getPdo()->prepare('INSERT INTO comment (content, user_id, poll_id, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->execute([
            $comment->getContent(),
            $comment->getUserId(),
            $comment->getPollId()
        ]);
        $comment->setId(intval(Mysql::getInstance()->getPdo()->lastInsertId()));
        $comment->setCreatedAt(date('Y-m-d H:i:s'));
        return $comment;
    }

    public function delete(int $id): bool {
        $stmt = Mysql::getInstance()->getPdo()->prepare('DELETE FROM comment WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
<?php

namespace App\Repository;

use App\Db\Mysql;
use App\Entity\UserPollItem;
use PDO;

class UserPollItemRepository
{
    public function __construct() {}

    public function hasVoted(int $user_id, int $poll_id): bool
    {
        $stmt = Mysql::getInstance()->getPdo()->prepare('SELECT upi.* FROM user_poll_item upi JOIN poll_item pi ON upi.poll_item_id = pi.id WHERE upi.user_id = ? AND pi.poll_id = ?');
        $stmt->execute([$user_id, $poll_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function addVote(UserPollItem $vote): UserPollItem
    {
        $stmt = Mysql::getInstance()->getPdo()->prepare('INSERT INTO user_poll_item (user_id, poll_item_id) VALUES (?, ?)');
        $stmt->execute([$vote->getUserId(), $vote->getPollItemId()]);
        return $vote;
    }

    public function countVotes(int $poll_id): array
    {
        $stmt = Mysql::getInstance()->getPdo()->prepare('SELECT pi.id, pi.name, COUNT(upi.user_id) as count FROM poll_item pi LEFT JOIN user_poll_item upi ON pi.id = upi.poll_item_id WHERE pi.poll_id = ? GROUP BY pi.id, pi.name');
        $stmt->execute([$poll_id]);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[$row['id']] = [
                'name' => $row['name'],
                'count' => $row['count']
            ];
        }
        return $results;
    }
    public function removeVotesForUserAndPoll(int $user_id, int $poll_id): void
    {
        $stmt = Mysql::getInstance()->getPdo()->prepare('
            DELETE upi FROM user_poll_item upi
            INNER JOIN poll_item pi ON upi.poll_item_id = pi.id
            WHERE upi.user_id = ? AND pi.poll_id = ?
        ');
        $stmt->execute([$user_id, $poll_id]);
    }

    public function getUserVote(int $user_id, int $poll_id): ?int
    {
        $stmt = Mysql::getInstance()->getPdo()->prepare('
            SELECT pi.id FROM user_poll_item upi
            INNER JOIN poll_item pi ON upi.poll_item_id = pi.id
            WHERE upi.user_id = ? AND pi.poll_id = ?
        ');
        $stmt->execute([$user_id, $poll_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? intval($data['id']) : null;
    }
}

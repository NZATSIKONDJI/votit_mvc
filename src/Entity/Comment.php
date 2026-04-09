<?php
namespace App\Entity;

class Comment {
    private ?int $id;
    private string $content;
    private int $user_id;
    private int $poll_id;
    private ?string $created_at;

    public function __construct(?int $id = null, string $content = '', int $user_id = 0, int $poll_id = 0, ?string $created_at = null) {
        $this->id = $id;
        $this->content = $content;
        $this->user_id = $user_id;
        $this->poll_id = $poll_id;
        $this->created_at = $created_at;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }
    public function getContent(): string { return $this->content; }
    public function setContent(string $content): void { $this->content = $content; }
    public function getUserId(): int { return $this->user_id; }
    public function setUserId(int $user_id): void { $this->user_id = $user_id; }
    public function getPollId(): int { return $this->poll_id; }
    public function setPollId(int $poll_id): void { $this->poll_id = $poll_id; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    public function setCreatedAt(string $created_at): void { $this->created_at = $created_at; }
}
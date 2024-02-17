<?php

declare(strict_types=1);

namespace App\Model;

class Comment
{
    protected int $id;
    protected string $body;
    protected string $createdAt;
    protected int $newsId;

    public function setId($id): Comment
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setBody($body): Comment
    {
        $this->body = $body;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setCreatedAt($createdAt): Comment
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getNewsId(): int
    {
        return $this->newsId;
    }

    public function setNewsId($newsId): Comment
    {
        $this->newsId = $newsId;

        return $this;
    }
}

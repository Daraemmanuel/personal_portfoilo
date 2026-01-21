<?php

use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\CommentReaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can like a comment', function () {
    $article = Article::factory()->create();
    $comment = ArticleComment::factory()->create([
        'article_id' => $article->id,
        'is_approved' => true,
    ]);

    $response = $this->post(route('comments.reactions.store', $comment->id), [
        'reaction_type' => 'like',
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('comment_reactions', [
        'comment_id' => $comment->id,
        'reaction_type' => 'like',
    ]);
});

test('user can mark comment as helpful', function () {
    $article = Article::factory()->create();
    $comment = ArticleComment::factory()->create([
        'article_id' => $article->id,
        'is_approved' => true,
    ]);

    $response = $this->post(route('comments.reactions.store', $comment->id), [
        'reaction_type' => 'helpful',
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('comment_reactions', [
        'comment_id' => $comment->id,
        'reaction_type' => 'helpful',
    ]);
});

test('user can remove reaction from comment', function () {
    $article = Article::factory()->create();
    $comment = ArticleComment::factory()->create([
        'article_id' => $article->id,
        'is_approved' => true,
    ]);

    $reaction = CommentReaction::factory()->create([
        'comment_id' => $comment->id,
        'reaction_type' => 'like',
        'ip_address' => '127.0.0.1',
    ]);

    $response = $this->post(route('comments.reactions.store', $comment->id), [
        'reaction_type' => 'like',
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseMissing('comment_reactions', [
        'id' => $reaction->id,
    ]);
});

test('cannot react to unapproved comment', function () {
    $article = Article::factory()->create();
    $comment = ArticleComment::factory()->create([
        'article_id' => $article->id,
        'is_approved' => false,
    ]);

    $response = $this->post(route('comments.reactions.store', $comment->id), [
        'reaction_type' => 'like',
    ]);

    $response->assertStatus(403);
});


<?php

namespace MyVendor\Nsfw;

use Flarum\Extend;
use Flarum\Tags\Api\Serializer\TagSerializer;
use MyVendor\Nsfw\Listener;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js')
        ->css(__DIR__ . '/less/forum.less'),
    (new Extend\Frontend('admin'))
        ->js(__DIR__ . '/js/dist/admin.js')
        ->css(__DIR__ . '/less/admin.less'),

    (new Extend\Routes('api'))
        ->post('/nsfw/check-access', 'nsfw.check-access', Controller\CheckAccessController::class),

    (new Extend\Model(Post::class))
        ->belongsToMany('tags', Tag::class, 'post_tags', 'post_id', 'tag_id'),

    (new Extend\Event())
        ->listen(Saving::class, Listener\AddNsfwAttribute::class),

    (new Extend\ApiSerializer(PostSerializer::class))
        ->attribute('isNsfw', function ($serializer, $post) {
            return $post->tags->contains(function ($tag) {
                return $tag->slug === 'nsfw';
            });
        }),
    (new Extend\ApiSerializer(BasicPostSerializer::class))
        ->attribute('isNsfw', function ($serializer, $post) {
            return $post->tags->contains(function ($tag) {
                return $tag->slug === 'nsfw';
            });
        }),
    (new Extend\ApiSerializer(TagSerializer::class))
        ->attribute('isNsfw', function (TagSerializer $serializer, Tag $tag) {
            return $tag->slug === 'nsfw';
    }),
    (new Extend\Settings)
        ->serializeToForum('nsfwTagSlug', 'my-vendor-nsfw.tag_slug', 'strval', 'nsfw'),

    (new Extend\Middleware('forum'))
        ->add(Middleware\CheckNsfwAccess::class),
    (new Extend\Middleware('api'))
        ->add(Middleware\CheckNsfwAccess::class),
    (new Extend\Locales(__DIR__ . '/locale')),

];
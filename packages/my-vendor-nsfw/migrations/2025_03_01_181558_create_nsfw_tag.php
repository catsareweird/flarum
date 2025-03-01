<?php

use Flarum\Tags\Tag;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        // Check if the tag already exists to prevent errors
        if (!Tag::where('slug', 'nsfw')->exists()) {
            $schema->getConnection()->table('tags')->insert([
                [
                    'name' => 'NSFW',
                    'slug' => 'nsfw',
                    'description' => 'Not Safe While Work content.',
                    'color' => '#ff8500', // Orange color
                    'position' => null,
                    'is_hidden' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'is_restricted' => 0,
                    'parent_id' => null, // Make it a primary tag
                ]
            ]);
        }
    },

    'down' => function (Builder $schema) {
        // We *could* delete the tag here, but it's generally safer
        // to leave it in case there are posts still associated with it.
        //  $schema->getConnection()->table('tags')->where('slug', 'nsfw')->delete();
    }
];
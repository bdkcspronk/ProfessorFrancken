<?php

declare(strict_types=1);

namespace Francken\Study\ResearchGroups;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Plank\Mediable\MediaUploader;

final class PhotoUploader
{
    public function __construct(private MediaUploader $uploader)
    {
    }

    public function uploadGroupPhoto(?UploadedFile $photo, ResearchGroup $group) : void
    {
        if ($photo === null) {
            return;
        }

        $media = $this->uploader->fromSource($photo)
            ->toDirectory("images/study/research-groups/{$group->slug}/")
            ->useFilename($group->slug)
            ->onDuplicateIncrement()
            ->upload();

        $group->update(['photo_media_id' => $media->id, 'photo_url' => null]);
        $group->syncMedia($media, ResearchGroup::PHOTO_TAG);
    }

    public function uploadUnitPhoto(?UploadedFile $photo, ResearchUnit $unit) : void
    {
        if ($photo === null) {
            return;
        }

        $slug = Str::slug($unit->name);
        $media = $this->uploader->fromSource($photo)
            ->toDirectory("images/study/research-groups/{$unit->researchGroup->slug}/")
            ->useFilename($slug)
            ->onDuplicateIncrement()
            ->upload();

        $unit->update(['photo_media_id' => $media->id, 'photo_url' => null]);
        $unit->syncMedia($media, ResearchUnit::PHOTO_TAG);
    }
}

<?php

declare(strict_types=1);

namespace Francken\Study\ResearchGroups;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Plank\Mediable\Media;

/**
 * A photo is either uploaded, or for those that came from the old website, a url
 *
 * @property int|null $photo_media_id
 * @property string|null $photo_url
 * @property-read Media|null $photoMedia
 */
trait HasPhoto
{
    public function getPhotoAttribute() : ?string
    {
        return $this->photoMedia?->getUrl() ?? $this->photo_url;
    }

    public function photoMedia() : BelongsTo
    {
        return $this->belongsTo(Media::class, 'photo_media_id');
    }
}

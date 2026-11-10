<?php

declare(strict_types=1);

namespace Francken\Study\ResearchGroups;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Plank\Mediable\Mediable;

/**
 * A field of research, such as Micromechanics, with the groups (units) that work on it
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $source_content
 * @property string|null $compiled_content
 * @property-read \Illuminate\Database\Eloquent\Collection|ResearchUnit[] $units
 */
final class ResearchGroup extends Model
{
    use HasPhoto;
    use Mediable;

    public const PHOTO_TAG = 'research_group_photo';

    protected $table = 'study_research_groups';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'source_content',
        'compiled_content',
        'photo_media_id',
        'photo_url',
    ];

    public function getRouteKeyName() : string
    {
        return 'slug';
    }

    public function units() : HasMany
    {
        return $this->hasMany(ResearchUnit::class, 'research_group_id')->orderBy('id');
    }
}

<?php

declare(strict_types=1);

namespace Francken\Study\ResearchGroups;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Plank\Mediable\Mediable;

/**
 * A group within a research group, led by a professor, such as the Onck group
 *
 * @property int $id
 * @property int $research_group_id
 * @property string $name
 * @property string $title
 * @property string|null $website_url
 * @property string|null $contact_url
 * @property-read ResearchGroup $researchGroup
 */
final class ResearchUnit extends Model
{
    use HasPhoto;
    use Mediable;

    public const PHOTO_TAG = 'research_unit_photo';

    protected $table = 'study_research_units';

    /**
     * @var string[]
     */
    protected $fillable = [
        'research_group_id',
        'name',
        'title',
        'website_url',
        'contact_url',
        'photo_media_id',
        'photo_url',
    ];

    public function researchGroup() : BelongsTo
    {
        return $this->belongsTo(ResearchGroup::class, 'research_group_id');
    }
}

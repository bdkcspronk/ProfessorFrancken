<?php

declare(strict_types=1);

namespace Francken\Association\Committees;

use Illuminate\Console\Command;

/**
 * Some committees have an empty image of a few hundred bytes as their photo, which
 * leaves an empty space on their page. This removes these photos from the committees,
 * so that their pages show no photo at all. The files themselves are kept, since the
 * committees of earlier boards share them.
 */
final class RemoveEmptyCommitteePhotos extends Command
{
    /**
     * Real photos are much larger than this
     */
    private const MAX_SIZE_OF_AN_EMPTY_PHOTO = 2048;

    /**
     * @var string
     */
    protected $signature = 'committees:remove-empty-photos {--dry-run : Only list the committees whose photo would be removed}';

    /**
     * @var string
     */
    protected $description = 'Remove the empty placeholder photos of committees';

    public function handle() : void
    {
        $committees = Committee::query()
            ->with(['board', 'photoMedia'])
            ->whereHas('photoMedia', fn ($query) => $query->where('size', '<=', self::MAX_SIZE_OF_AN_EMPTY_PHOTO))
            ->get();

        foreach ($committees as $committee) {
            $this->line(sprintf(
                '%s (%s): %d bytes',
                $committee->name,
                $committee->board->board_year->toString(),
                $committee->photoMedia->size
            ));

            if ($this->option('dry-run')) {
                continue;
            }

            $committee->detachMediaTags(Committee::COMMITEE_PHOTO_TAG);
            $committee->photo_media_id = null;
            $committee->save();
        }

        $this->info(sprintf(
            $this->option('dry-run') ? 'The photos of %d committees would be removed.' : 'Removed the photos of %d committees.',
            $committees->count()
        ));
    }
}

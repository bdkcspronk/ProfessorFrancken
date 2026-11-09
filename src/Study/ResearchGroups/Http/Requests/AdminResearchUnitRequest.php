<?php

declare(strict_types=1);

namespace Francken\Study\ResearchGroups\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

final class AdminResearchUnitRequest extends FormRequest
{
    /**
     * @var int
     */
    private const MAX_FILE_SIZE = 10 * 1024;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules() : array
    {
        return [
            'name' => ['required', 'min:1'],
            'title' => ['required', 'min:1'],
            'website_url' => ['nullable', 'url'],
            'contact_url' => ['nullable', 'url'],
            'photo' => ['nullable', 'image', 'max:' . self::MAX_FILE_SIZE],
        ];
    }

    public function name() : string
    {
        return $this->string('name')->toString();
    }

    public function title() : string
    {
        return $this->string('title')->toString();
    }

    public function websiteUrl() : ?string
    {
        return $this->filled('website_url') ? $this->string('website_url')->toString() : null;
    }

    public function contactUrl() : ?string
    {
        return $this->filled('contact_url') ? $this->string('contact_url')->toString() : null;
    }

    public function photo() : ?UploadedFile
    {
        $photo = $this->file('photo');

        return $photo instanceof UploadedFile ? $photo : null;
    }
}

<?php

declare(strict_types=1);

namespace Francken\Study\ResearchGroups\Http\Requests;

use Francken\Study\ResearchGroups\ResearchGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

final class AdminResearchGroupRequest extends FormRequest
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
        $group = $this->route('researchGroup');

        return [
            'title' => ['required', 'min:1'],
            // The slug comes from the title and is the address of the page of the group
            'slug' => [
                Rule::unique('study_research_groups', 'slug')
                    ->ignore($group instanceof ResearchGroup ? $group->id : null),
            ],
            'source_content' => ['nullable', 'min:1'],
            'photo' => ['nullable', 'image', 'max:' . self::MAX_FILE_SIZE],
        ];
    }

    public function messages() : array
    {
        return ['slug.unique' => 'There already is a research group with this title.'];
    }

    public function title() : string
    {
        return $this->string('title')->toString();
    }

    public function slug() : string
    {
        return Str::slug($this->title());
    }

    public function content() : string
    {
        return $this->string('source_content')->toString();
    }

    public function photo() : ?UploadedFile
    {
        $photo = $this->file('photo');

        return $photo instanceof UploadedFile ? $photo : null;
    }

    protected function prepareForValidation() : void
    {
        $this->merge(['slug' => Str::slug($this->string('title')->toString())]);
    }
}

<div class="row">
    <div class="col-md-3">
        <img
            id="research-group-photo"
            alt="Photo of the research group"
            src="{{ $group->photo ? image($group->photo, ['width' => 600, 'height' => 300]) : '' }}"
            class="mb-3 img-fluid rounded"
            style="object-fit: cover"
        />

        <x-forms.image-upload
            name="photo"
            label="Photo"
            output-image-id="research-group-photo"
        >
            <x-slot name="help">
                <small class="form-text text-muted">
                    Shown on the card of the research group and at the top of its page.
                </small>
            </x-slot>
        </x-forms.image-upload>
    </div>
    <div class="col-md-9">
        <x-forms.text name="title" label="Title" placeholder="Micromechanics" required>
            <x-slot name="help">
                The title also determines the address of the page of the research group.
            </x-slot>
        </x-forms.text>
        <x-forms.markdown label="Description" />
    </div>
</div>

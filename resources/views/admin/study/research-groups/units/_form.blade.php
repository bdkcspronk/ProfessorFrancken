<div class="row">
    <div class="col-md-3">
        <img
            id="research-unit-photo"
            alt="Photo of the group"
            src="{{ $unit?->photo ? image($unit->photo, ['width' => 200, 'height' => 200]) : '' }}"
            class="mb-3 img-fluid rounded"
            style="object-fit: cover"
        />

        <x-forms.image-upload
            name="photo"
            label="Photo"
            output-image-id="research-unit-photo"
        >
            <x-slot name="help">
                <small class="form-text text-muted">
                    Usually a photo of the professor who leads the group.
                </small>
            </x-slot>
        </x-forms.image-upload>
    </div>
    <div class="col-md-9">
        <x-forms.text name="name" label="Name" placeholder="Onck" required>
            <x-slot name="help">
                Usually the name of the professor, it is shown as "Onck group".
            </x-slot>
        </x-forms.text>
        <x-forms.text name="title" label="Title" placeholder="Micromechanics of Cellular and Active Materials" required />
        <x-forms.text name="website_url" label="Website" placeholder="https://www.rug.nl/research/zernike/..." />
        <x-forms.text name="contact_url" label="Contact page" placeholder="https://www.rug.nl/staff/..." />
    </div>
</div>

// The fields for a date show and take the date as yyyy-mm-dd in every browser. Browsers show
// their own date fields in the format of their language (like mm/dd/yyyy), so flatpickr
// replaces them; it is only loaded on pages with a date field. The value that is sent stays
// yyyy-mm-dd, like before
export function setupDateInputs() {
    const inputs = document.querySelectorAll('input[type="date"]');
    if (inputs.length === 0) {
        return;
    }

    Promise.all([
        import('flatpickr'),
        import('./date-inputs.css'),
    ]).then(([{ default: flatpickr }]) => {
        inputs.forEach((input) => {
            flatpickr(input, {
                dateFormat: 'Y-m-d',
                allowInput: true,
                // The same picker on phones, instead of their own one in another format
                disableMobile: true,
                minDate: input.min || null,
                maxDate: input.max || null,
                locale: { firstDayOfWeek: 1 },
                onReady: (selectedDates, value, instance) => {
                    // Typing works as well, so it doesn't look like a field that can't be typed in
                    instance.input.removeAttribute('readonly');
                    instance.input.placeholder = 'yyyy-mm-dd';
                },
            });
        });
    });
}

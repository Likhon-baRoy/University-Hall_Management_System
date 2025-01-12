$(document).ready(function() {
    // Enable modal dismissal with Escape key
    $(document).keydown(function(e) {
        if (e.key === "Escape") {
            $('.modal').modal('hide');
        }
    });

    // Update custom file input label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);

        // Preview image
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#photoPreview img').attr('src', e.target.result);
                $('#photoPreview').show();
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Close photo modal when edit modal is opened
    $('#edit_details').on('show.bs.modal', function() {
        $('#updatePhotoModal').modal('hide');
    });

    // Reset photo preview when modal is closed
    $('#updatePhotoModal').on('hidden.bs.modal', function() {
        $('#photoPreview').hide();
        $('.custom-file-label').html('Select file');
        $('.custom-file-input').val('');
    });

    // Form validation
    $('form').on('submit', function(e) {
        let requiredFields = $(this).find('[required]');
        let valid = true;

        requiredFields.each(function() {
            if (!$(this).val()) {
                valid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!valid) {
            e.preventDefault();
            alert('Please fill in all required fields');
        }
    });

    // Password match validation
    $('input[name="password"], input[name="password_confirmation"]').on('keyup', function() {
        let password = $('input[name="password"]').val();
        let confirm = $('input[name="password_confirmation"]').val();

        if (password && confirm) {
            if (password !== confirm) {
                $('input[name="password_confirmation"]').addClass('is-invalid');
                $('.password-match-feedback').remove();
                $('input[name="password_confirmation"]').after(
                    '<div class="invalid-feedback password-match-feedback">Passwords do not match</div>'
                );
            } else {
                $('input[name="password_confirmation"]').removeClass('is-invalid');
                $('.password-match-feedback').remove();
            }
        }
    });

    // Add required fields validation
    $('#edit_details form').find('input[name="name"], input[name="email"], input[name="cell"]').prop('required', true);

    // Initialize date picker for DOB field if needed
    if ($.fn.datepicker) {
        $('input[name="dob"]').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            endDate: new Date()
        });
    }

    // Character counter for bio
    $('textarea[name="bio"]').on('keyup', function() {
        let maxLength = 1000;
        let length = $(this).val().length;
        let remaining = maxLength - length;

        if (!$(this).next('.char-count').length) {
            $(this).after('<small class="char-count text-muted"></small>');
        }

        $(this).next('.char-count').text(`${remaining} characters remaining`);

        if (remaining < 0) {
            $(this).addClass('is-invalid');
            $(this).next('.char-count').removeClass('text-muted').addClass('text-danger');
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.char-count').removeClass('text-danger').addClass('text-muted');
        }
    });

    // Phone number formatting
    $('input[name="cell"]').on('input', function() {
        let number = $(this).val().replace(/[^\d]/g, '');
        if (number.length >= 10) {
            number = number.substring(0, 10);
            number = number.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
        }
        $(this).val(number);
    });

    // Show confirmation before canceling changes
    $('.modal .btn-secondary').on('click', function(e) {
        let formChanged = false;
        $(this).closest('.modal').find('input, textarea').each(function() {
            if ($(this).val() !== $(this).prop('defaultValue')) {
                formChanged = true;
                return false;
            }
        });

        if (formChanged) {
            if (!confirm('Are you sure you want to discard your changes?')) {
                e.preventDefault();
            }
        }
    });
});

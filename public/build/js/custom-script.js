// delete confirmation start

$(".confirm-text2").on("click", function (e) {
    e.preventDefault();

    // Get the associated delete form
    var deleteForm = $(this).closest("tr").find(".delete-form");

    // Show the SweetAlert confirmation dialog
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        // icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            deleteForm.submit();
        }
    });
});

// delete confirmation end

// image preview start

$(".image-input").change(function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $(this)
                .closest(".profile-pic-upload")
                .find(".image-preview")
                .attr("src", e.target.result);
            $(this)
                .closest(".profile-pic-upload")
                .find(".remove-photo")
                .removeClass("d-none");
        }.bind(this); // Bind `this` to access the input element in the callback
        reader.readAsDataURL(file);
    }
});
$(document).on("click", ".remove-photo", function () {
    $(this)
        .closest(".profile-pic-upload")
        .find(".image-preview")
        .attr("src", defaultUploadImagePath);
    $(this).closest(".profile-pic-upload").find(".image-input").val("");
    $(this).addClass("d-none");
});

// image preview end

// copy text
$(document).ready(function () {
    $(document).on("click", ".copyable", function () {
        const textToCopy = $(this).text().trim();
        
        // Function to show success feedback
        const showCopyFeedback = () => {
            $(this).addClass("copied");
            setTimeout(() => {
                $(this).removeClass("copied");
            }, 1000);
        };
        
        // Try modern clipboard API first (HTTPS only)
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard
                .writeText(textToCopy)
                .then(() => {
                    showCopyFeedback();
                })
                .catch((err) => {
                    console.warn("Clipboard API failed, using fallback method:", err);
                    fallbackCopy(textToCopy, showCopyFeedback);
                });
        } else {
            // Fallback method for HTTP or older browsers
            fallbackCopy(textToCopy, showCopyFeedback);
        }
    });
    
    // Fallback copy function using temporary textarea
    function fallbackCopy(text, callback) {
        try {
            // Create temporary textarea element
            const textarea = document.createElement("textarea");
            textarea.value = text;
            textarea.style.position = "fixed";
            textarea.style.left = "-9999px";
            textarea.style.top = "-9999px";
            textarea.style.opacity = "0";
            
            // Add to DOM, select, copy, and remove
            document.body.appendChild(textarea);
            textarea.focus();
            textarea.select();
            textarea.setSelectionRange(0, 99999); // For mobile devices
            
            const successful = document.execCommand('copy');
            document.body.removeChild(textarea);
            
            if (successful) {
                callback();
            } else {
                throw new Error('Copy command failed');
            }
        } catch (err) {
            console.error("Fallback copy method failed:", err);
            // Optional: Show user-friendly error message
            alert("Copy failed. Please copy the text manually.");
        }
    }
});

// copy text end

// Delete Media

function deleteMedia({
    model,
    model_id,
    media_id = null,
    collection_name = null,
}) {
    $.ajax({
        url: window.Laravel.routes.deleteMedia,
        type: "DELETE",
        data: {
            model,
            model_id,
            media_id,
            collection_name,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            console.log("media deleted");
        },
        error: function (xhr, status, error) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    toastr.error(value);
                });
            } else {
                toastr.error("Something went wrong");
            }
        },
    });
}

// Delete Media End

function toggleSide() {

        const sidebar = document.querySelector(".sidebar");
        const main = document.querySelector(".main");
        const button = document.getElementById("sidebarToggle");
        const icon = document.getElementById("sideIcon");

        if (sidebar.classList.contains("closed")) {
            sidebar.classList.remove("closed");
            button.setAttribute("aria-expanded", "true");
            icon.classList.remove("bi-arrow-bar-right");
            icon.classList.add("bi-arrow-bar-left");
            main.style.marginLeft = "200px";
            main.style.width = "calc(100% - 200px)";
        }

        else {
            main.style.width = "100%";
            sidebar.classList.add("closed");
            main.style.marginLeft = "0";
            button.setAttribute("aria-expanded", "false");
            icon.classList.remove("bi-arrow-bar-left");
            icon.classList.add("bi-arrow-bar-right");
        }
    }

// for failures in create meeting (bad times)
document.addEventListener("DOMContentLoaded", function () {
    const createMeetingModal = document.getElementById("createMeetingForm");
    if (createMeetingModal && createMeetingModal.dataset.show === "true") {
        const modal = new bootstrap.Modal(createMeetingModal);
        modal.show();
    }

    // get rid of the darkened, unusable background that sticks
    createMeetingModal.addEventListener("hidden.bs.modal", function () {
        document.body.classList.remove("modal-open");
        document.querySelectorAll(".modal-backdrop").forEach(el => el.remove());
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const editModal = document.querySelector('.modal[data-edit-modal="true"]');
    if (editModal) {
        const modal = new bootstrap.Modal(editModal);
        modal.show();

        editModal.addEventListener("hidden.bs.modal", function () {
            document.body.classList.remove("modal-open");
            document.querySelectorAll(".modal-backdrop").forEach(el => el.remove());
        });
    }
});

function showFrame(id, btn) {
    var frame = document.getElementById('document' + id);

    if (frame.style.display === 'none' || frame.style.display === '') {
        frame.style.display = 'block';
        btn.textContent = 'Hide document';
    } else {
        frame.style.display = 'none';
        btn.textContent = 'Show document here';
    }
}

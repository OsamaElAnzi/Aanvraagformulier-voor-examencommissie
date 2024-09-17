function bewijsMeeSturen() {
    const ja = document.getElementById("bewijsja");
    const fileContainer = document.getElementById("fileContainer");

    if (ja.checked) {
        fileContainer.innerHTML =
            `<div class="mb-3">
            <input class="form-control" type="file" id="file" name="file" required>
        </div>`;
    } else {
        fileContainer.innerHTML = '';
    }
}

function addInput(containerId, inputName) {
    const container = document.getElementById(containerId);
    const newInput = document.createElement('div');
    newInput.classList.add('input-group', 'select-container');
    newInput.innerHTML =
        `<input type="text" class="form-control" name="${inputName}[]" autocomplete="off" required>
    <button type="button" class="btn btn-danger remove-btn">-</button>`;
    container.appendChild(newInput);
    const removeBtn = newInput.querySelector('.remove-btn');
    removeBtn.addEventListener('click', () => {
        container.removeChild(newInput);
    });
}
const addButtons = document.querySelectorAll('.add-btn');
addButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        const containerId = btn.getAttribute('data-container');
        const inputName = containerId === 'examennaamContainer' ? 'examenNaam' : 'examenCode';
        addInput(containerId, inputName);
    });
});
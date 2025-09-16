function showModal() {
    document.getElementById('otpModal').style.display = 'flex';
    }

function closeModal() {
    document.getElementById('otpModal').style.display = 'none';
    }

function handleInput(input) {
    if (input.value.length === 1 && input.nextElementSibling) {
        input.nextElementSibling.focus();
        }
    }

function handleKeyDown(input, event) {
    if (event.key === 'Backspace') {
        if (input.value === '' && input.previousElementSibling) {
            input.previousElementSibling.focus();
        }
    }
            if (!/^[0-9]$/.test(event.key) && event.key !== 'Backspace') {
                event.preventDefault();
            }
         }
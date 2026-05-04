/**
 * LMLinga — Auth Pages Client-Side Logic
 * Handles form validation & redirection (static flow)
 */

// Form validation helpers
function showError(input, message) {
    input.classList.add('input-error');
    let err = input.nextElementSibling;
    if (!err || !err.classList.contains('error-message')) {
        err = document.createElement('span');
        err.className = 'error-message';
        input.parentNode.insertBefore(err, input.nextSibling);
    }
    err.textContent = message;
}

function clearError(input) {
    input.classList.remove('input-error');
    let err = input.nextElementSibling;
    if (err && err.classList.contains('error-message')) err.remove();
}

function validateRequired(field, name) {
    clearError(field);
    if (!field.value.trim()) {
        showError(field, name + ' is required');
        return false;
    }
    return true;
}

// Registration form handler
function handleRegister() {
    const firstName = document.getElementById('firstName');
    const lastName = document.getElementById('lastName');
    const household = document.getElementById('householdNo');
    const password = document.getElementById('regPassword');
    
    let valid = true;
if (!validateRequired(firstName, 'First name')) valid = false;
    if (!validateRequired(lastName, 'Last name')) valid = false;
    if (!validateRequired(household, 'Household number')) valid = false;
    if (!validateRequired(password, 'Password')) valid = false;
    
    if (valid) {
        // Redirect to login after successful registration
        window.location.href = '/login';
    }
}

// Login form handler
function handleLogin() {
    const household = document.getElementById('loginHousehold');
    const password = document.getElementById('loginPassword');
    
    let valid = true;
    if (!validateRequired(household, 'Household number')) valid = false;
    if (!validateRequired(password, 'Password')) valid = false;
    
    if (valid) {
        // Redirect to chatbot after successful login
        window.location.href = '/chatbot';
    }
}

// Language toggle (UI only)
function setLang(btn) {
    document.querySelectorAll('.lm-lang-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

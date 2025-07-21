// script.js

document.addEventListener('DOMContentLoaded', () => {
    const nextBtns = document.querySelectorAll('.next-btn');
    const prevBtns = document.querySelectorAll('.prev-btn');
    const formSteps = document.querySelectorAll('.form-step');
    const loginForm = document.querySelector('.login-form');
    let currentStep = 0;


    const updateFormSteps = () => {
        formSteps.forEach((step, index) => {
            step.classList.toggle('form-step-active', index === currentStep);
        });

        const activeStep = formSteps[currentStep];
        const activeStepHeight = activeStep.scrollHeight + 160;
        loginForm.style.minHeight = `${activeStepHeight}px`;
    };

    updateFormSteps();

    nextBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep < formSteps.length - 1) {
                currentStep++;
                updateFormSteps();
            }
        });
    });

    prevBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep > 0) {
                currentStep--;
                updateFormSteps();
            }
        });
    });
});

const floorButtons = document.querySelectorAll('.unique-floor-btn');
const formSteps = document.querySelectorAll('.unique-form-step');

floorButtons.forEach(button => {
    button.addEventListener('click', () => {
        const step = button.getAttribute('data-step');
        showStep(step);
    });
});

function showStep(step) {
    formSteps.forEach((formStep, index) => {
        formStep.classList.toggle('unique-form-step-active', index == step);
    });
}
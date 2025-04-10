const dropdown = document.getElementById('dropdown');
const options = document.getElementById('options');
const selected = document.getElementById('selected');

dropdown.addEventListener('click', () => {
  options.classList.toggle('hidden');
});

options.addEventListener('click', (e) => {
  if (e.target.tagName === 'LI') {
    selected.textContent = e.target.textContent;
    options.classList.add('hidden');
  }
});

// Optional: close when clicking outside
document.addEventListener('click', (e) => {
  if (!dropdown.contains(e.target) && !options.contains(e.target)) {
    options.classList.add('hidden');
  }
});
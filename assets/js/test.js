const searchInput = document.getElementById('searchInput');
const cardsName = document.querySelectorAll('.cards .name');

searchInput.addEventListener('input', () => {
  const searchValue = searchInput.value.toLowerCase();

  cardsName.forEach(item => {
    const text = item.textContent.toLowerCase();
    item.style.display = text.includes(searchValue) ? '' : 'none';
  });
});

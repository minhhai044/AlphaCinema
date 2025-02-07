const handleDelete = (id) => {
  showAlertConfirm(() => {
    $(`#delete-cinema-${id}`).submit();
  });
};

const { z } = Zod;

const schema = z.object({
  branch_id: z.string().min(1, "Vui lòng chọn chi nhánh"),
  name: z
    .string()
    .min(1, "Trường tên không được để trống")
    .max(255, "Tối đa 255 kí tự"),
  slug: z.string().optional(),
  address: z.string().min(1, "Trường địa chỉ không được để trống"),
  description: z.string().optional(),
  is_active: z.enum(["0", "1"]).optional(),
});

const handleSubmit = (event) => {
  event.preventDefault();

  const name = $("#cinema-name").val();
  const branch_id = $("#cinema-branch_id").val();
  const address = $("#cinema-address").val();
  const description = $("#cinema-description").val();

  try {
    schema.parse({ name, branch_id, address, description });
    console.log("Validate success");
    $("#cinema-form").off("submit").submit();
  } catch (error) {
    console.log(error);

    handleValidateErrors("cinema", error);
  }
};

$("#cinema-name").on("input", (e) =>
  handleValidateField("cinema", schema, "name", e.target.value)
);
$("#cinema-branch_id").on("change", (e) =>
  handleValidateField("cinema", schema, "branch_id", e.target.value)
);
$("#cinema-address").on("input", (e) =>
  handleValidateField("cinema", schema, "address", e.target.value)
);
$("#cinema-description").on("input", (e) =>
  handleValidateField("cinema", schema, "description", e.target.value)
);
/**
 * Submit
 */
$("#cinema-form").on("submit", handleSubmit);

@extends('layouts.app')

@section('content')

    <style>
        .btn-success {
            transition: 0.3s ease;
        }

        .btn-success:hover {
            transform: scale(1.05);
        }
    </style>

    <div class="mb-4 pb-2 border-bottom border-primary">
        <h2 class="text-dark fw-bold">
            <i class="bi bi-box-seam me-2"></i>Products
        </h2>
    </div>
    
    <form class="p-4 rounded-2 shadow bg-white" method="post" name="product-form" id="product_form">
        @csrf
        <h5 class="mb-4 text-primary fw-bold">
            Add New Product
        </h5>

        <div class="row g-3">
            <!-- Product Name -->
            <div class="col-md-3">
                <label for="product_name" class="form-label">Product Name</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                    <input type="text" name="product_name" class="form-control" id="product_name"
                        placeholder="e.g., iPhone 16" required>
                </div>
            </div>

            <!-- Quantity -->
            <div class="col-md-3">
                <label for="quantity" class="form-label">Quantity in Stock</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-archive"></i></span>
                    <input type="number" name="quantity" class="form-control" id="quantity" placeholder="e.g., 100"
                        min="1" required>
                </div>
            </div>

            <!-- Price -->
            <div class="col-md-3">
                <label for="price" class="form-label">Price per Item</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                    <input type="number" step="0.01" name="price" class="form-control" id="price"
                        placeholder="e.g., 199.00" min="0.01" required>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" id="submit" class="btn btn-success">
                    <i class="bi bi-save me-1"></i>Save Product
                </button>
            </div>

        </div>
    </form>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <form id="edit_product_form">
            @csrf
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="route_url" id="edit_route_url">
                <input type="hidden" name="index" id="edit_index">
                <div class="mb-3">
                    <label for="edit_product_name" class="form-label">Product Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-tag"></i></span>
                        <input type="text" name="product_name" class="form-control" id="edit_product_name"
                            placeholder="e.g., iPhone 16" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="edit_quantity" class="form-label">Quantity in Stock</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-archive"></i></span>
                        <input type="number" name="quantity" class="form-control" id="edit_quantity" placeholder="e.g., 100"
                            min="1" required>
                    </div>
                </div>
                <div class="md-3">
                    <label for="edit_price" class="form-label">Price per Item</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                        <input type="number" step="0.01" name="price" class="form-control" id="edit_price"
                            placeholder="e.g., 199.00" min="0.01" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Update Product</button>
            </div>
            </div>
        </form>
        </div>
    </div>
  

    <div id="product-table"></div>

@endsection

@section('js_content')
<script>
    function loadTable(){
        $.get('/products/list', function(data) {
             $('#product-table').html(data);
        });
    }

    $(document).ready(function () {
        $("#product_form").on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("product_add") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function () {
                    toastr.success("Product added successfully");
                    $("#product_form").trigger("reset");
                    loadTable();
                },
                error: function (xhr) {
                    toastr.error('Failed to added product');
                    console.log(xhr);
                }
            });
        });
    });

    $(document).ready(function () {
        // Open Modal & Fill data
        $(document).on('click', '.edit-btn', function () {
            const routeUrl = $(this).data('url');
            const index = $(this).data('index');
            const name = $(this).data('name');
            const quantity = $(this).data('quantity');
            const price = $(this).data('price');
            
            $('#edit_route_url').val(routeUrl);
            $('#edit_index').val(index);
            $('#edit_product_name').val(name);
            $('#edit_quantity').val(quantity);
            $('#edit_price').val(price);

            $('#editProductModal').modal('show');
        });

        // AJAX for updating
        $('#edit_product_form').on('submit', function (e) {
            e.preventDefault();

            const routeUrl = $('#edit_route_url').val();

            $.ajax({
                url: routeUrl,
                method: 'PUT',
                data: $(this).serialize(),
                success: function () {
                    toastr.success('Product updated successfully');
                    $('#editProductModal').modal('hide');
                    loadTable(); // Reload table data
                },
                error: function () {
                    toastr.error('Failed to update product');
                }
            });
        });
    });

    $(document).on('click', '.delete-btn', function () {
        const routeUrl = $(this).data('url');

        if (!confirm('Are you sure you want to delete this product?')) return;

        $.ajax({
            url: routeUrl,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function () {
                toastr.success('Product deleted successfully');
                loadTable();
            },
            error: function (xhr) {
                toastr.error('Failed to delete product');
            }
        });
    });

    loadTable();
</script>

<script>
    toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-right", // Top-right corner
    "preventDuplicates": true,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "2000", // Auto-hide after 3 seconds
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};
</script>
@endsection




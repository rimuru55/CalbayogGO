<?php
// Include the database connection
include '../Data/contents.php';
global $conn;

// Check if there's a search query
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the SQL query to include the search filter
$sql = "SELECT * FROM contents";
if (!empty($searchQuery)) {
    $sql .= " WHERE title LIKE ? OR description LIKE ? OR category LIKE ? OR address LIKE ?";
}

$stmt = $conn->prepare($sql);

if (!empty($searchQuery)) {
    // Bind the search query to the SQL statement
    $likeQuery = '%' . $searchQuery . '%';
    $stmt->bind_param("ssss", $likeQuery, $likeQuery, $likeQuery, $likeQuery);
}

$stmt->execute();
$result = $stmt->get_result();
$contents = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();


// Fetch contents
$sql = "SELECT * FROM contents";
$result = $conn->query($sql);
$contents = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Manage Contents</title>
  <link rel="stylesheet" href="../libs/admin.css"> <!-- Adjusted path for CSS -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="script.js"></script>
</head>

<body>
  <div class="container">
    <h1>Manage Contents</h1>

    <!-- Search and Filter Section -->
    <div class="content-management-search-filter">
      <input type="text" id="content-search" placeholder="Search by title, description, or address">
      <select id="filter-category">
        <option value="">Filter by Category</option>
        <option value="Waterfalls">Waterfalls</option>
        <option value="Beach">Beach</option>
        <option value="Nature Park">Nature Park</option>
        <option value="Hotels">Hotels</option>
        <option value="Food Place">Food Place</option>
        <option value="Religious Sites, Churches & Cathedrals">Religious Sites</option>
      </select>
    </div>

    <button id="addContentBtn" class="btn primary-btn">Add Content</button>

    <!-- Add Content Modal -->
    <div id="addContentModal" class="add-contents-manage-modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <form id="addContentForm" method="POST" action="../Data/contents.php" enctype="multipart/form-data">
          <input type="hidden" name="action" value="create">
          <div class="form-group">
            <label for="category">Category</label>
               <select id="category" name="category" required>
                  <option value="Waterfalls">Waterfalls</option>
                  <option value="Beach">Beach</option>
                  <option value="Nature Park">Nature Park</option>
                  <option value="Hotels">Hotels</option>
                  <option value="Food Place">Food Place</option>
                  <option value="Religious Sites, Churches & Cathedrals">Religious Sites</option>
              </select>
          </div>
          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>
          </div>
          <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" required></textarea>
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" required>
          </div>
          <div class="form-group">
              <label for="latitude">Latitude</label>
              <input type="text" id="latitude" name="latitude" required>
          </div>
          <div class="form-group">
              <label for="longitude">Longitude</label>
              <input type="text" id="longitude" name="longitude" required>
          </div>
          <div class="form-group">
            <label for="price">Price</label>
            <input type="text" id="price" name="price">
          </div>

          <div class="form-group">
            <label for="amenities">Amenities</label>
            <div class="checkbox-group">
              <label><input type="checkbox" name="amenities[]" value="Wifi"> Wi-Fi</label>
              <label><input type="checkbox" name="amenities[]" value="Parking"> Parking</label>
              <label><input type="checkbox" name="amenities[]" value="Beach"> Beach</label>
              <label><input type="checkbox" name="amenities[]" value="Pool"> Pool</label>
              <label><input type="checkbox" name="amenities[]" value="Outdoor Pool"> Outdoor Pool</label>
              <label><input type="checkbox" name="amenities[]" value="Cottage"> Cottage</label>
              <label><input type="checkbox" name="amenities[]" value="Bangquet Room"> Banquet Room</label>
              <label><input type="checkbox" name="amenities[]" value="Non-Smoking Hotel"> Non-Smoking Hotel</label>
              <label><input type="checkbox" name="amenities[]" value="Nature Spot"> Nature Spot</label>
              <label><input type="checkbox" name="amenities[]" value="Outdoor Activity"> Outdoor Activity</label>
              <label><input type="checkbox" name="amenities[]" value="Historical Site"> Historical Site</label>
            </div>
          </div>

                    <!-- Mode of Transportation Checkbox Group -->
          <div class="form-group">
              <label for="transportation">Mode of Transportation</label>
              <div class="checkbox-group">
                  <label><input type="checkbox" name="transportation[]" value="Car"> Car</label>
                  <label><input type="checkbox" name="transportation[]" value="Bike"> Bike</label>
                  <label><input type="checkbox" name="transportation[]" value="Public Transport"> Public Transport</label>
                  <label><input type="checkbox" name="transportation[]" value="Walking"> Walking</label>
              </div>
          </div>

          <!-- Things to Do Textarea -->
          <div class="form-group">
              <label for="things_to_do">Things to Do</label>
              <textarea id="things_to_do" name="things_to_do" required></textarea>
          </div>

            <!-- Cover Photo Upload -->
            <div class="form-group">
                <label for="cover-photo">Cover Photo</label>
                <input type="file" id="cover-photo" name="cover_photo" required>
            </div>

            <!-- Additional Photos Upload -->
            <div class="form-group">
                <label for="pictures">Additional Pictures</label>
                <input type="file" id="pictures" name="pictures[]" multiple>
            </div>

          <button type="submit" class="btn primary-btn">Add Content</button>
        </form>
      </div>
    </div>

    <!-- Edit Content Modal -->
    <div id="editContentModal" class="edit-contents-manage-modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <form id="editContentForm" method="POST" action="../Data/contents.php" enctype="multipart/form-data">
          <input type="hidden" name="action" value="update">
          <input type="hidden" name="id" id="edit-content-id">
          
          <div class="form-group">
            <label for="category">Category</label>
            <select id="edit-category" name="category" required>
              <option value="Waterfalls">Waterfalls</option>
              <option value="Beach">Beach</option>
              <option value="Nature Park">Nature Park</option>
              <option value="Hotels">Hotels</option>
              <option value="Food Place">Food Place</option>
              <option value="Religious Sites, Churches & Cathedrals">Religious Sites</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="edit-title">Title</label>
            <input type="text" id="edit-title" name="title" required>
          </div>
          
          <div class="form-group">
            <label for="edit-description">Description</label>
            <textarea id="edit-description" name="description" required></textarea>
          </div>
          
          <div class="form-group">
            <label for="edit-address">Address</label>
            <input type="text" id="edit-address" name="address" required>
          </div>
          <div class="form-group">
              <label for="edit-latitude">Latitude</label>
              <input type="text" id="edit-latitude" name="latitude">
          </div>
          <div class="form-group">
              <label for="edit-longitude">Longitude</label>
              <input type="text" id="edit-longitude" name="longitude">
          </div>
          
          <div class="form-group">
            <label for="edit-price">Price</label>
            <input type="text" id="edit-price" name="price">
          </div>

          <div class="form-group">
            <label for="edit-amenities">Amenities</label>
            <div class="checkbox-group">
              <label><input type="checkbox" name="amenities[]" value="Wifi" id="edit-wifi"> Wi-Fi</label>
              <label><input type="checkbox" name="amenities[]" value="Parking" id="edit-parking"> Parking</label>
              <label><input type="checkbox" name="amenities[]" value="Beach" id="edit-beach"> Beach</label>
              <label><input type="checkbox" name="amenities[]" value="Pool" id="edit-pool"> Pool</label>
              <label><input type="checkbox" name="amenities[]" value="Outdoor Pool" id="edit-outdoor_pool"> Outdoor Pool</label>
              <label><input type="checkbox" name="amenities[]" value="Cottage" id="edit-cottage"> Cottage</label>
              <label><input type="checkbox" name="amenities[]" value="Bangquet Room" id="edit-banquet_room"> Banquet Room</label>
              <label><input type="checkbox" name="amenities[]" value="Non-Smoking Hotel" id="edit-non_smoking_hotel"> Non-Smoking Hotel</label>
              <label><input type="checkbox" name="amenities[]" value="Nature Spot" id="edit-nature_spot"> Nature Spot</label>
              <label><input type="checkbox" name="amenities[]" value="Outdoor Activity" id="edit-outdoor_activity"> Outdoor Activity</label>
              <label><input type="checkbox" name="amenities[]" value="Historical Site" id="edit-historical_site"> Historical Site</label>
            </div>
          </div>

          <!-- Mode of Transportation Checkbox Group -->
          <div class="form-group">
              <label for="edit-transportation">Mode of Transportation</label>
              <div class="checkbox-group">
                  <label><input type="checkbox" name="transportation[]" value="Car" id="edit-car"> Car</label>
                  <label><input type="checkbox" name="transportation[]" value="Bike" id="edit-bike"> Bike</label>
                  <label><input type="checkbox" name="transportation[]" value="Public Transport" id="edit-public_transport"> Public Transport</label>
                  <label><input type="checkbox" name="transportation[]" value="Walking" id="edit-walking"> Walking</label>
              </div>
          </div>

          <!-- Things to Do Textarea -->
          <div class="form-group">
              <label for="edit-things_to_do">Things to Do</label>
              <textarea id="edit-things_to_do" name="things_to_do"></textarea>
          </div>


          <!-- Cover Photo Upload -->
          <div class="form-group">
            <label for="edit-cover-photo">Cover Photo</label>
            <input type="file" id="edit-cover-photo" name="cover_photo">
          </div>

          <!-- Additional Photos Upload -->
          <div class="form-group">
            <label for="edit-pictures">Additional Pictures</label>
            <input type="file" id="edit-pictures" name="pictures[]" multiple>
          </div>
          
          <button type="submit" class="btn primary-btn">Save Changes</button>
        </form>
      </div>
    </div>

    <!-- Content Table -->
    <div class="table-container">
      <table class="styled-table">
      <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Address</th>
            <th>Price</th>
            <th>Picture</th>
            <th>Category</th>
            <th>Actions</th>
        </tr>
      </thead>
      <tbody id="contentTable">
          <?php foreach ($contents as $content): ?>
              <tr data-title="<?php echo htmlspecialchars($content['title']); ?>"
                  data-description="<?php echo htmlspecialchars($content['description']); ?>"
                  data-category="<?php echo htmlspecialchars($content['category']); ?>"
                  data-price="<?php echo htmlspecialchars($content['price']); ?>">
                  <td><?php echo htmlspecialchars($content['title']); ?></td>
                  <td><?php echo htmlspecialchars($content['description']); ?></td>
                  <td><?php echo htmlspecialchars($content['address']); ?></td>
                  <td><?php echo !empty($content['price']) ? htmlspecialchars($content['price']) : 'N/A'; ?></td>
                  <td><img src="<?php echo "../uploads/" . htmlspecialchars($content['cover_photo']); ?>" alt="<?php echo htmlspecialchars($content['title']); ?>" width="100"></td>
                  <td><?php echo htmlspecialchars($content['category']); ?></td>
                  <td>
                      <button class="edit-content-btn" data-id="<?php echo $content['id']; ?>">Edit</button>
                      <form method="POST" action="../Data/contents.php" class="delete-content-form">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $content['id']; ?>">
                        <button type="submit" class="delete-content-btn">Delete</button>
                      </form>
                  </td>
              </tr>
          <?php endforeach; ?>
      </tbody>
      </table>
    </div>
  </div>
  <?php $conn->close(); // Close the database connection here, after all operations are complete ?>
  <script>
    $(document).ready(function() {
      // Open add content modal
      $('#addContentBtn').on('click', function() {
        $('#addContentModal').show();
      });

      // Open edit content modal
      $('.edit-content-btn').on('click', function() {
          const contentId = $(this).data('id');
          // Fetch content details via AJAX and populate the edit form
          $.ajax({
              url: '../Data/contents.php',
              type: 'POST',
              data: { action: 'get_content', id: contentId },
              success: function(response) {
                  const content = JSON.parse(response);
                  $('#edit-content-id').val(content.id);
                  $('#edit-title').val(content.title);
                  $('#edit-description').val(content.description);
                  $('#edit-address').val(content.address);
                  $('#edit-latitude').val(content.latitude);
                  $('#edit-longitude').val(content.longitude);
                  $('#edit-price').val(content.price);
                  $('#edit-category').val(content.category);
                  $('#edit-things_to_do').val(content.things_to_do); // Populate Things to Do

                  // Populate amenities checkboxes
                  const amenities = content.amenities ? JSON.parse(content.amenities) : [];
                  $('.checkbox-group input[type="checkbox"]').prop('checked', false); // Reset all checkboxes
                  amenities.forEach(function (amenity) {
                      const checkbox = $(`#edit-${amenity.toLowerCase().replace(' ', '_')}`);
                      if (checkbox) {
                          checkbox.prop('checked', true);
                      }
                  });

                  // Populate mode of transportation checkboxes
                  const transportation = content.transportation ? JSON.parse(content.transportation) : [];
                  $('#edit-car').prop('checked', transportation.includes('Car'));
                  $('#edit-bike').prop('checked', transportation.includes('Bike'));
                  $('#edit-public_transport').prop('checked', transportation.includes('Public Transport'));
                  $('#edit-walking').prop('checked', transportation.includes('Walking'));

                  $('#editContentModal').show();
              }
          });
      });


      // Close modals
      $('.close').on('click', function() {
        $(this).closest('.add-contents-manage-modal, .edit-contents-manage-modal').hide();
      });


      // Delete content
      $('.delete-content-btn').on('click', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        if (confirm('Are you sure you want to delete this content?')) {
          form.submit();
        }
      });

      $('#addContentForm').on('submit', function(e) {
          e.preventDefault(); // Prevent the default form submission

          const formData = new FormData(this);
          
          // Get transportation data and add it to the form data
          const transportation = [];
          $('#addContentForm .checkbox-group input[name="transportation[]"]:checked').each(function() {
              transportation.push($(this).val());
          });
          formData.append('transportation', JSON.stringify(transportation)); // Serialize as JSON

          $.ajax({
              url: '../Data/contents.php',
              type: 'POST',
              data: formData,
              contentType: false,
              processData: false,
              success: function(response) {
                  alert(response); // Show success or error message
                  
                  // Get form values to add marker to map
                  const latitude = parseFloat($('#latitude').val());
                  const longitude = parseFloat($('#longitude').val());
                  const title = $('#title').val();

                  // Add marker to map dynamically
                  new mapboxgl.Marker()
                      .setLngLat([longitude, latitude])
                      .setPopup(new mapboxgl.Popup({ offset: 25 }) // Add popups
                          .setText(title))
                      .addTo(map);

                  // Hide the modal after adding content
                  $('#addContentModal').hide();
                  $('#addContentForm')[0].reset();
              },
              error: function(err) {
                  console.error(err);
              }
          });
      });

      $('#editContentForm').on('submit', function(e) {
          e.preventDefault(); // Prevent the default form submission

          const formData = new FormData(this);
          
          // Get transportation data and add it to the form data
          const transportation = [];
          $('#editContentForm .checkbox-group input[name="transportation[]"]:checked').each(function() {
              transportation.push($(this).val());
          });
          formData.append('transportation', JSON.stringify(transportation)); // Serialize as JSON

          $.ajax({
              url: '../Data/contents.php',
              type: 'POST',
              data: formData,
              contentType: false,
              processData: false,
              success: function(response) {
                  alert(response); // Show success or error message

                  // Hide the modal after saving changes
                  $('#editContentModal').hide();
                  $('#editContentForm')[0].reset();
              },
              error: function(err) {
                  console.error(err);
              }
          });
      });



          // Function to filter contents
    function filterContents() {
        const searchQuery = $('#content-search').val().toLowerCase();
        const filterCategory = $('#filter-category').val();
        const filterPrice = $('#filter-price').val();

        $('#contentTable tr').each(function() {
            const title = $(this).data('title').toLowerCase();
            const description = $(this).data('description').toLowerCase();
            const category = $(this).data('category');
            const price = $(this).data('price');

            const matchesSearch = title.includes(searchQuery) || description.includes(searchQuery);
            const matchesCategory = !filterCategory || category === filterCategory;

            // Define price ranges for filtering
            let matchesPrice = true;
            if (filterPrice === 'free') {
                matchesPrice = price === 'N/A' || price === '';
            } else if (filterPrice === 'affordable') {
                matchesPrice = parseFloat(price.replace(/[^0-9]/g, '')) <= 1000;
            } else if (filterPrice === 'moderate') {
                matchesPrice = parseFloat(price.replace(/[^0-9]/g, '')) > 1000 && parseFloat(price.replace(/[^0-9]/g, '')) <= 3000;
            } else if (filterPrice === 'expensive') {
                matchesPrice = parseFloat(price.replace(/[^0-9]/g, '')) > 3000;
            }

            // Show or hide the row based on filters
            $(this).toggle(matchesSearch && matchesCategory && matchesPrice);
        });
    }

    // Attach event listeners to inputs and filters
    $('#content-search').on('input', filterContents);
    $('#filter-category').on('change', filterContents);
    $('#filter-price').on('change', filterContents);

    });

  </script>
</body>
</html>

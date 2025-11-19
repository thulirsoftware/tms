<div class="modal fade" id="addClientModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content add-client-modal">

      <!-- Modal Header -->
      <div class="modal-header modal-header-gradient">
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title text-white">
          Add Client
        </h2>

      </div>
      <style>
        .modal-header-gradient button span {
          font-size: 30px;
          margin-top: 10px;
          color: white;

        }
      </style>


      <!-- Modal Body -->
      <div class="modal-body">
        <form id="clientForm">
          @csrf

          <div class="form-section">
            <label class="fw-bold">Name</label>
            <input type="text" name="name" class="form-control form-input" required>
          </div>

          <div class="form-section">
            <label class="fw-bold">Description</label>
            <textarea name="description" class="form-control form-input"></textarea>
          </div>

          <div class="form-section">
            <label class="fw-bold">Location</label>
            <input type="text" name="location" class="form-control form-input">
          </div>

          <hr class="divider">
          <h6 class="section-title"><i class="fa fa-briefcase mr-2"></i> Projects</h6>
          <div id="projects-wrap"></div>
          <button type="button" class="btn btn-sm btn-outline-primary add-btn" id="addProject">+ Add Project</button>

          <hr class="divider">
          <h6 class="section-title"><i class="fa fa-cogs mr-2"></i> Services</h6>
          <div id="services-wrap"></div>
          <button type="button" class="btn btn-sm btn-outline-primary add-btn" id="addService">+ Add Service</button>
        </form>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn save-btn" id="saveClient">
          Save
        </button>
      </div>
    </div>
  </div>
</div>

<style>
  /* 🌈 Modal Appearance */
  .add-client-modal {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    animation: modalFadeIn 0.5s ease;
  }

  .modal-header-gradient {
    background: linear-gradient(90deg, #007bff, #6610f2);
    color: #fff;
    padding: 15px 25px;
    border-bottom: none;
  }

  .modal-header .close {
    opacity: 0.9;
    font-size: 1.4rem;
  }

  .modal-header .close:hover {
    opacity: 1;
    transform: scale(1.2);
  }

  /* 🌿 Modal Body */
  .modal-body {
    background-color: #f9fbff;
    padding: 25px;
  }

  .form-section {
    margin-bottom: 15px;
  }

  .form-input {
    border-radius: 10px;
    border: 1px solid #d4d9e1;
    transition: all 0.3s ease;
    box-shadow: none;
  }

  .form-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
  }

  /* ✨ Section Titles */
  .section-title {
    color: #444;
    font-weight: 600;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
  }

  .divider {
    border-color: #e0e6ef;
  }

  /* 🧩 Add Buttons */
  .add-btn {
    border-radius: 20px;
    transition: 0.3s ease;
    font-weight: 500;
  }

  .add-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
  }

  /* 💾 Save Button */
  .save-btn {
    background: linear-gradient(90deg, #28a745, #20c997);
    border: none;
    color: #fff;
    font-weight: 500;
    padding: 8px 20px;
    border-radius: 30px;
    transition: all 0.3s ease;
  }

  .save-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(40, 167, 69, 0.4);
  }

  /* 🔲 Dynamic Project/Service Box */
  #projects-wrap>div,
  #services-wrap>div {
    background: #fff;
    border-radius: 10px;
    border: 1px solid #dee2e6;
    padding: 12px;
    margin-bottom: 12px;
    transition: 0.3s ease;
  }

  #projects-wrap>div:hover,
  #services-wrap>div:hover {
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
    transform: scale(1.01);
  }

  /* 🌟 Modal Animation */
  @keyframes modalFadeIn {
    from {
      opacity: 0;
      transform: translateY(-15px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* 🔹 Unified Dynamic Box Styling */
  .project-box,
  .service-box {
    background: #fff;
    border-radius: 10px;
    border: 1px solid #dee2e6;
    padding: 16px 20px 12px 20px;
    margin-bottom: 14px;
    position: relative;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  /* Hover Animation */
  .project-box:hover,
  .service-box:hover {
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
  }

  /* 🔸 Project Row Layout */
  .project-box .project-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }

  .project-box .project-row input[type="text"],
  .project-box .project-row input[type="date"] {
    flex: 1;
    min-width: 160px;
    border-radius: 8px;
    border: 1px solid #d0d7e2;
    padding: 8px 12px;
    transition: all 0.3s ease;
  }

  .project-box .project-row input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
  }

  /* Description below the row */
  .project-box textarea {
    width: 100%;
    min-height: 60px;
    border-radius: 8px;
    border: 1px solid #d0d7e2;
    padding: 8px 12px;
    transition: all 0.3s ease;
  }

  .project-box textarea:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
  }

  /* 🔹 Service Box Layout */
  .service-box .service-row {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .service-box input[type="text"],
  .service-box textarea {
    width: 100%;
    border-radius: 8px;
    border: 1px solid #d0d7e2;
    padding: 8px 12px;
    transition: all 0.3s ease;
  }

  .service-box input:focus,
  .service-box textarea:focus {
    border-color: #6610f2;
    box-shadow: 0 0 0 3px rgba(102, 16, 242, 0.15);
  }

  /* ❌ Remove Button */
  .remove-box {
    position: absolute;
    top: 6px;
    right: 8px;
    background: #ff4d4d;
    color: white;
    border: none;
    border-radius: 50%;
    font-size: 18px;
    line-height: 18px;
    width: 26px;
    height: 26px;
    cursor: pointer;
    text-align: center;
    transition: 0.3s ease;
  }

  .remove-box:hover {
    background: #e60000;
    transform: scale(1.15);
    box-shadow: 0 3px 8px rgba(255, 0, 0, 0.3);
  }

  /* 🔸 Responsive Adjustments */
  @media (max-width: 768px) {
    .project-box .project-row {
      flex-direction: column;
    }
  }
</style>

{{-- ✅ Load jQuery and Bootstrap JS first --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  $(document).ready(function () {

    // Delete client (unchanged)
    $(document).on('click', '.delete-client', function () {
      if (confirm('Delete this client?')) {
        let id = $(this).data('id');
        $.ajax({
          url: `/clients/${id}`,
          type: 'DELETE',
          data: { _token: '{{ csrf_token() }}' },
          success: () => location.reload()
        });
      }
    });

    // Add project
    let projectIndex = 0;
    $('#addProject').click(function () {
      $('#projects-wrap').append(`
    <div class="project-box animate-in">
      <button type="button" class="remove-box">&times;</button>
      <div class="project-row">
        <input type="text" name="projects[${projectIndex}][projectName]" placeholder="Project Name">
        <input type="date" name="projects[${projectIndex}][startDate]" placeholder="Start Date">
        <input type="date" name="projects[${projectIndex}][endDate]" placeholder="End Date">
      </div>
      <textarea name="projects[${projectIndex}][projectDesc]" placeholder="Description"></textarea>
    </div>
  `);
      projectIndex++;
    });

    // Add service
    let serviceIndex = 0;
    $('#addService').click(function () {
      $('#services-wrap').append(`
    <div class="service-box animate-in">
      <button type="button" class="remove-box">&times;</button>
      <div class="service-row">
        <input type="text" name="services[${serviceIndex}][name]" placeholder="Service Name">
        <textarea name="services[${serviceIndex}][description]" placeholder="Description"></textarea>
      </div>
    </div>
  `);
      serviceIndex++;
    });

    // Remove dynamically added box (project/service)
    $(document).on('click', '.remove-box', function () {
      const box = $(this).closest('.project-box, .service-box');
      box.addClass('animate-out');
      setTimeout(() => box.remove(), 300);
    });

    // Save client
    $('#saveClient').click(function () {
      $.ajax({
        url: '{{ route("clients.store") }}',
        method: 'POST',
        data: $('#clientForm').serialize(),
        success: function (res) {
          if (res.success) {
            location.reload();
          } else {
            alert('Something went wrong.');
          }
        },
        error: function (xhr, status, error) {
            if (xhr.status === 419) {
                alert('CSRF token mismatch');
                location.reload(true);
            } else {
                console.error("Error: " + error);
                alert('An error occurred. Please try again later.');
            }
        } 
      });
    });

  });
</script>
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Generate Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>Please select the type of report you want to generate:</p>
                <div class="d-grid gap-2">
                    <button class="btn btn-primary report-trigger" data-type="today" data-bs-dismiss="modal">📅 Today</button>
                    <button class="btn btn-primary report-trigger" data-type="week" data-bs-dismiss="modal">🗓️ This Week</button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#monthSelectModal">📆 Select Month</button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#yearSelectModal">📊 Select Year</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="monthSelectModal" tabindex="-1" aria-labelledby="monthSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="monthSelectModalLabel">Select Month</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="reportMonthInput" class="form-label">Month and Year</label>
                <input type="month" id="reportMonthInput" class="form-control" value="<?= date('Y-m') ?>">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="generateReport('month')">Generate</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="yearSelectModal" tabindex="-1" aria-labelledby="yearSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="yearSelectModalLabel">Select Year</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="reportYearInput" class="form-label">Year</label>
                <select id="reportYearInput" class="form-select">
                    <?php $currentYear = date('Y'); for ($y = $currentYear; $y >= 2000; $y--): ?>
                        <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="generateReport('year')">Generate</button>
            </div>
        </div>
    </div>
</div>


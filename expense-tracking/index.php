<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Spendwise | Expense Tracker</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="layout">
  <aside class="sidebar">
    <div class="logo"><div class="logo-symbol">s.</div><div><b>spendwise</b><small>PERSONAL FINANCE</small></div></div>
    <div class="menu-caption">MENU</div>
    <a class="menu-link selected" href="#"><span>▥</span> Overview</a>
    <a class="menu-link" href="#transactions"><span>↕</span> Transactions</a>
    <div class="side-note"><div class="note-icon">✦</div><b>Small steps add up.</b><p>Keep track of your money and make every decision count.</p></div>
    <div class="profile"><div class="profile-avatar">U</div><div><b>My Workspace</b><small>Personal account</small></div><span class="dots">•••</span></div>
  </aside>
  <main class="content">
    <header class="header"><div><div class="date-line" id="todayLabel">YOUR FINANCE OVERVIEW</div><h1>Good money habits<br><em>start here.</em></h1></div><div class="header-right"><span class="live-mark"></span> All transactions synced locally</div></header>
    <section class="summary-grid">
      <article class="summary-card balance-card"><div class="card-label">TOTAL BALANCE <span class="card-symbol">◉</span></div><div class="money" id="balanceValue">PKR 0.00</div><div class="card-foot"><span>Income minus expenses</span><span class="tiny-arrow">↗</span></div><div class="balance-decoration"></div></article>
      <article class="summary-card"><div class="card-label">TOTAL INCOME <span class="round-icon income-icon">↗</span></div><div class="money dark-money" id="incomeValue">PKR 0.00</div><div class="card-foot muted-foot">Money received</div></article>
      <article class="summary-card"><div class="card-label">TOTAL EXPENSES <span class="round-icon expense-icon">↘</span></div><div class="money dark-money" id="expenseValue">PKR 0.00</div><div class="card-foot muted-foot">Money spent</div></article>
    </section>

    <section class="content-grid">
      <div class="main-panel" id="transactions">
        <div class="section-head"><div><div class="section-kicker">YOUR MONEY ACTIVITY</div><h2>Transactions</h2></div><button class="add-btn" id="addBtn">＋ Add transaction</button></div>
        <div class="filters"><div class="search"><span>⌕</span><input id="searchInput" placeholder="Search transactions..."></div><input type="month" id="monthFilter" aria-label="Filter by month"></div>
        <div class="transaction-list" id="transactionList"><div class="empty-state">Loading transactions...</div></div>
        <div class="list-footer"><span id="recordCount">Loading...</span><span>Most recent first</span></div>
      </div>
      <aside class="category-panel"><div class="section-kicker">SPENDING BREAKDOWN</div><h2>Where it goes</h2><p class="category-sub">Expense totals by category</p><div id="categoryList" class="category-list"><div class="empty-state">Loading...</div></div><div class="category-tip"><span>✦</span><div><b>Stay mindful</b><p>Review your spending categories regularly to spot patterns.</p></div></div></aside>
    </section>
    <footer>SPENDWISE · A CLOUD SPARK PROJECT <span>Built for smarter everyday budgeting</span></footer>
  </main>
</div>

<div class="modal-layer" id="modal" aria-hidden="true"><div class="modal">
  <div class="modal-top"><div><div class="section-kicker">MONEY MOVEMENT</div><h2 id="modalTitle">Add transaction</h2><p>Record income or an expense.</p></div><button id="closeModal" class="close">×</button></div>
  <form id="transactionForm">
    <input type="hidden" id="recordId">
    <label>Transaction title<input id="title" required maxlength="120" placeholder="e.g. Grocery shopping"></label>
    <div class="type-toggle"><label><input type="radio" name="type" value="expense" checked><span class="type-expense">↘ Expense</span></label><label><input type="radio" name="type" value="income"><span class="type-income">↗ Income</span></label></div>
    <div class="two-col"><label>Amount (PKR)<input id="amount" type="number" min="0.01" step="0.01" required placeholder="0.00"></label><label>Date<input id="date" type="date" required></label></div>
    <label>Category<select id="category" required><option value="">Choose category</option><option>Food & Dining</option><option>Transport</option><option>Shopping</option><option>Bills & Utilities</option><option>Education</option><option>Health</option><option>Salary</option><option>Freelance</option><option>Other</option></select></label>
    <label>Notes <span class="optional">(optional)</span><textarea id="notes" rows="2" placeholder="Add a short note..."></textarea></label>
    <div class="form-bottom"><button type="button" class="cancel-btn" id="cancelBtn">Cancel</button><button class="save-btn" id="saveBtn">Save transaction</button></div><div id="formMessage" class="form-message"></div>
  </form>
</div></div>
<div class="toast" id="toast"></div>
<script src="assets/app.js"></script>
</body></html>

# TODO

- [ ] Update `resources/views/invoices/pdf.blade.php` to make invoice PDF layout more modern + DomPDF-friendly.
- [ ] Update `app/Http/Controllers/InvoiceController.php` to allow admin users to view/download invoice PDFs.
- [ ] Add admin invoice routes in `routes/web.php` under `/admin/billing/...`.
- [ ] Update `resources/views/admin/billing.blade.php` to use real “View” and “Download PDF” links (no alerts).
- [ ] (After) Verify URLs work: `/invoices/8/pdf` renders nicely and admin can access `/admin/billing/...` view/download.


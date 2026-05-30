/**
 * Beasiswa UI behavior.
 *
 * Initial state:
 * - Halaman form sudah ter-render dengan nilai IPK (read-only).
 * Final state:
 * - Jika IPK < 3.0: pilihan beasiswa, upload berkas, dan submit dinonaktifkan.
 * - Jika IPK >= 3.0: komponen aktif dan fokus otomatis ke pilihan beasiswa.
 *
 * @author sep sarip hidayattuloh
 *
 * @since 30 Mei 2026
 */

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("[data-beasiswa-form]");
    if (!form) return;

    const ipkInput = form.querySelector("[data-ipk]");
    const pilihanSelect = form.querySelector("[data-pilihan]");
    const berkasInput = form.querySelector("[data-berkas]");
    const submitButton = form.querySelector("[data-submit]");
    const nomorHpInput = form.querySelector("#nomor_hp");

    const rawIpk = (ipkInput?.value ?? "").trim();
    const ipk = Number.parseFloat(rawIpk.replace(",", "."));
    const eligible = Number.isFinite(ipk) && ipk >= 3.0;

    const setDisabled = (element, disabled) => {
        if (!element) return;
        element.disabled = !!disabled;
        element.setAttribute("aria-disabled", disabled ? "true" : "false");
    };

    setDisabled(pilihanSelect, !eligible);
    setDisabled(berkasInput, !eligible);
    setDisabled(submitButton, !eligible);

    if (eligible && pilihanSelect) {
        pilihanSelect.focus();
    }

    // Nomor HP: hanya angka
    if (nomorHpInput) {
        nomorHpInput.addEventListener("input", () => {
            const next = nomorHpInput.value.replace(/\D+/g, "");
            if (next !== nomorHpInput.value) nomorHpInput.value = next;
        });
    }
});

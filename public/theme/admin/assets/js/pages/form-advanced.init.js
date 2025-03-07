document.addEventListener("DOMContentLoaded", function () {
    var e = document.querySelectorAll("[data-trigger]");
    for (i = 0; i < e.length; ++i) {
        var a = e[i];
        new Choices(a, {
            placeholderValue: "This is a placeholder set in the config",
            searchPlaceholderValue: "This is a search placeholder",
        });
    }
    new Choices("#choices-single-no-search", {
        searchEnabled: !1,
        removeItemButton: !0,
        choices: [
            { value: "One", label: "Label One" },
            { value: "Two", label: "Label Two", disabled: !0 },
            { value: "Three", label: "Label Three" },
        ],
    }).setChoices(
        [
            { value: "Four", label: "Label Four", disabled: !0 },
            { value: "Five", label: "Label Five" },
            { value: "Six", label: "Label Six", selected: !0 },
        ],
        "value",
        "label",
        !1
    ),
        new Choices("#choices-single-no-sorting", { shouldSort: !1 }),
        new Choices("#choices-multiple-remove-button", {
            removeItemButton: !0,
        }),
        new Choices(document.getElementById("choices-multiple-groups")),
        new Choices(document.getElementById("choices-text-remove-button"), {
            delimiter: ",",
            editItems: !0,
            maxItemCount: 5,
            removeItemButton: !0,
        }),
        new Choices("#choices-text-unique-values", {
            paste: !1,
            duplicateItemsAllowed: !1,
            editItems: !0,
        }),
        new Choices("#choices-text-disabled", {
            addItems: !1,
            removeItems: !1,
        }).disable();
});
flatpickr("#datepicker-basic", { defaultDate: new Date() }),
    flatpickr("#datepicker-datetime", {
        enableTime: !0,
        dateFormat: "m-d-Y H:i",
        defaultDate: new Date(),
    }),
    flatpickr("#datepicker-humanfd", {
        altInput: !0,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        defaultDate: new Date(),
    }),
    flatpickr("#datepicker-minmax", {
        minDate: "today",
        defaultDate: new Date(),
        maxDate: new Date().fp_incr(14),
    }),
    flatpickr("#datepicker-disable", {
        onReady: function () {
            this.jumpToDate("2025-01");
        },
        disable: [
            "2025-01-30",
            "2025-02-21",
            "2025-03-08",
            new Date(2025, 4, 9),
        ],
        dateFormat: "Y-m-d",
        defaultDate: new Date(),
    }),
    flatpickr("#datepicker-multiple", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        defaultDate: null,
    }),
    flatpickr("#datepicker-range", { mode: "range", defaultDate: new Date() }),
    flatpickr("#datepicker-timepicker", {
        enableTime: !0,
        noCalendar: !0,
        dateFormat: "H:i",
        defaultDate: new Date(),
    }),
    flatpickr("#datepicker-inline", { inline: !0, defaultDate: new Date() });

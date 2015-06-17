package com.dvsa.mot.selenium.datasource;

public class DateRange {

    public static final DateRange VALID_DATE =
            new DateRange("01", "01", "2015", "09", "01", "2015");
    public static final DateRange PUBLIC_HOLIDAY =
            new DateRange("25", "12", "2014", "25", "12", "2014");
    public static final DateRange INVALID_DATE =
            new DateRange("32", "01", "2014", "30", "02", "2014");

    public final String startDay;
    public final String startMonth;
    public final String startYear;
    public final String endDay;
    public final String endMonth;
    public final String endYear;

    public DateRange(String startDay, String startMonth, String startYear, String endDay,
            String endMonth, String endYear) {
        this.startDay = startDay;
        this.startMonth = startMonth;
        this.startYear = startYear;
        this.endDay = endDay;
        this.endMonth = endMonth;
        this.endYear = endYear;
    }
}

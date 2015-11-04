package uk.gov.dvsa.domain.model.mot;

public class DateRange {
    private int day;
    private int month;
    private int year;

    public DateRange(int day, int month, int year) {
        this.day = day;
        this.month = month;
        this.year = year;
    }

    public String getStringDay() {
        return String.valueOf(day);
    }

    public String getStringMonth() {
        return String.valueOf(month);
    }

    public String getStringYear() {
        return String.valueOf(year);
    }

    public int getIntDay() {
        return day;
    }

    public int getIntMonth() {
        return month;
    }

    public int getIntYear() {
        return year;
    }
}

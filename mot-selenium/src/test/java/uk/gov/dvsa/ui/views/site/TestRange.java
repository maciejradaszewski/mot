package uk.gov.dvsa.ui.views.site;

import org.joda.time.DateTime;

public class TestRange {


    public static void main(String[] args) {
        DateTime firstTestDate = DateTime.now().withDayOfMonth(2);
        DateTime secondTestDate = DateTime.now().minusDays(28).withDayOfMonth(30);

        System.out.println(firstTestDate.dayOfMonth().getAsString() + "-" + firstTestDate.monthOfYear().getAsString()
        + "-" + firstTestDate.year().getAsString());

        System.out.println(secondTestDate.dayOfMonth().getAsString() + "-" + secondTestDate.monthOfYear().getAsString()
        + "-" + secondTestDate.year().getAsString());

    }
}

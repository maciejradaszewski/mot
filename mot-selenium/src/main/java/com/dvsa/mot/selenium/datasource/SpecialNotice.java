package com.dvsa.mot.selenium.datasource;


import com.dvsa.mot.selenium.datasource.enums.VehicleClasses;
import org.joda.time.LocalDate;

public class SpecialNotice {
    public final String title;
    public static final SpecialNotice SPECIAL_NOTICE_VALID =
            new SpecialNotice("TITLE", new LocalDate(), new LocalDate(),
                    AcknowledgementPeriods.eight, new VehicleClasses[]{VehicleClasses.one},
                    "Body");
    public final LocalDate internalPublishDate;
    public static final SpecialNotice SPECIAL_NOTICE_CONTAINING_JAVASCRIPT =
            new SpecialNotice("Notice", new LocalDate(), new LocalDate(),
                    AcknowledgementPeriods.eight, new VehicleClasses[]{VehicleClasses.one},
                    "[irm](javascript:prompt('PleaseEnterYourPasswordToContinue'))");
    public final LocalDate externalPublishDate;
    public final AcknowledgementPeriods acknowledgementPeriod;
    public final VehicleClasses[] recipients;
    public final String body;

    public SpecialNotice(String title, LocalDate internalPublishDate, LocalDate externalPublishDate,
                         AcknowledgementPeriods acknowledgementPeriod, VehicleClasses[] recipients,
                         String body) {
        super();
        this.title = title;
        this.internalPublishDate = internalPublishDate;
        this.externalPublishDate = externalPublishDate;
        this.acknowledgementPeriod = acknowledgementPeriod;
        this.recipients = recipients;
        this.body = body;
    }

    public enum AcknowledgementPeriods {
        eight("8"), sixteen("16");

        private final String id;

        private AcknowledgementPeriods(String id) {
            this.id = id;
        }

        public String getId() {
            return this.id;
        }
    }


    ;


}

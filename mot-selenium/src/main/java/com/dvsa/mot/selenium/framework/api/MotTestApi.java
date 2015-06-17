package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.ReasonForRejection;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.api.helper.RequestorAttachment;
import org.joda.time.DateTime;
import org.joda.time.DateTimeZone;
import org.joda.time.Period;
import org.joda.time.format.DateTimeFormat;

import javax.json.Json;
import javax.json.JsonArrayBuilder;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;
import java.util.ArrayList;
import java.util.List;
import java.util.Objects;

public class MotTestApi extends BaseApi {


    public MotTestApi() {
        super(testSupportUrl(), null);
    }

    /**
     * Creates normal mot test with optional retest data
     *
     * @param requestor
     * @param vehicle
     * @param vtsId
     * @param testData
     * @param retestData if not null, then retest is created for the normal test as well
     * @return mot test number
     */
    public String createTest(Login requestor, Vehicle vehicle, int vtsId, MotTestData testData,
            RetestData retestData) {

        Objects.requireNonNull(vehicle);
        Objects.requireNonNull(testData);

        JsonArrayBuilder arrayBuilder = Json.createArrayBuilder();

        for (ReasonForRejection rfr : testData.rfrs) {
            arrayBuilder.add(Json.createObjectBuilder().add("id", rfr.reasonId));
        }

        JsonObjectBuilder data = Json.createObjectBuilder();
        RequestorAttachment.attach(requestor, data);
        data.add("vehicleId", vehicle.carID).add("vtsId", vtsId).add("motTest",
                Json.createObjectBuilder().add("mileage", testData.mileage)
                        .add("outcome", testData.outcome.toString())
                        .add("issueDate", dateToString(testData.issueDate))
                        .add("startDate", dateTimeToString(testData.startDate))
                        .add("completedDate", dateTimeToString(testData.completedDate))
                        .add("expiryDate", dateToString(testData.expiryDate))
                        .add("rfrs", arrayBuilder));

        if (retestData != null) {
            data.add("retest", Json.createObjectBuilder().add("mileage", retestData.mileage)
                    .add("outcome", retestData.outcome.toString())
                    .add("issueDate", dateToString(retestData.issueDate))
                    .add("startDate", dateTimeToString(retestData.startDate))
                    .add("completedDate", dateTimeToString(retestData.completedDate))
                    .add("expiryDate", dateToString(retestData.expiryDate)));
        }

        JsonObject result = post("testsupport/mottest", data.build());
        return result.getJsonObject("data").getString("motTestNumber");
    }

    private static String dateToString(DateTime date) {
        return date.toString(("YYYY-MM-dd"));
    }

    private static String dateTimeToString(DateTime date) {
        return date.withZone(DateTimeZone.UTC)
                .toString(DateTimeFormat.forPattern("YYYY-MM-dd'T'HH:mm:ss'Z'"));
    }

    public enum TestOutcome {
        PASSED, FAILED, PRS;
    }


    public enum RetestOutcome {
        PASSED, FAILED
    }


    public static class MotTestData {
        public List<ReasonForRejection> rfrs = new ArrayList<>();
        public final int mileage;
        public final TestOutcome outcome;
        public final DateTime issueDate;
        public final DateTime startDate;
        public final DateTime completedDate;
        public final DateTime expiryDate;

        public MotTestData(TestOutcome outcome, int mileage, String issueDate) {
            this(outcome, mileage, DateTime.parse(issueDate));
        }

        public MotTestData(TestOutcome outcome, int mileage, DateTime issuedDate) {
            this(outcome, mileage, issuedDate, issuedDate.minus(Period.hours(2)),
                    issuedDate.minus(Period.hours(1)),
                    issuedDate.plus(Period.years(1).minusDays(1)));
        }

        public MotTestData(TestOutcome outcome, int mileage, DateTime issuedDate,
                DateTime startDate, DateTime completedDate, DateTime expiryDate) {
            this.mileage = mileage;
            this.outcome = outcome;
            this.issueDate = issuedDate;
            this.startDate = startDate;
            this.completedDate = completedDate;
            this.expiryDate = expiryDate;
        }

        public void setRfrs(List<ReasonForRejection> rfrs) {
            this.rfrs = rfrs;
        }
    }


    public static class RetestData {
        public final int mileage;
        public final RetestOutcome outcome;
        public final DateTime issueDate;
        public final DateTime startDate;
        public final DateTime completedDate;
        public final DateTime expiryDate;

        public RetestData(RetestOutcome outcome, int mileage, String issuedDate) {
            this(outcome, mileage, DateTime.parse(issuedDate));
        }

        public RetestData(RetestOutcome outcome, int mileage, DateTime issuedDate) {
            this(outcome, mileage, issuedDate, issuedDate.plus(Period.hours(10)),
                    issuedDate.plus(Period.hours(11)), issuedDate.plus(Period.years(1)));
        }

        public RetestData(RetestOutcome outcome, int mileage, DateTime issuedDate,
                DateTime startDate, DateTime completedDate, DateTime expiryDate) {
            this.outcome = outcome;
            this.mileage = mileage;
            this.issueDate = issuedDate;
            this.startDate = startDate;
            this.completedDate = completedDate;
            this.expiryDate = expiryDate;
        }
    }

}

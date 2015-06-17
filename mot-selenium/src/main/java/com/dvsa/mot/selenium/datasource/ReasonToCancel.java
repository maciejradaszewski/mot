package com.dvsa.mot.selenium.datasource;

import com.dvsa.mot.selenium.datasource.enums.Reason;

public class ReasonToCancel {
    public static final ReasonToCancel REASON_ACCIDENT_OR_ILLNESS =
            new ReasonToCancel(Reason.AccidentOrIllness, "");
    public static final ReasonToCancel REASON_ABORTED_BY_VE =
            new ReasonToCancel(Reason.AbortedByVE, "");
    public static final ReasonToCancel REASON_VEHICLE_REGISTERED_ERROR =
            new ReasonToCancel(Reason.VehicleRegisteredInError, "");
    public static final ReasonToCancel REASON_TEST_EQUIPMENT_ISSUE =
            new ReasonToCancel(Reason.testEquipmentIssue, "");
    public static final ReasonToCancel REASON_VTS_INCIDENT =
            new ReasonToCancel(Reason.VTSincident, "");
    public static final ReasonToCancel REASON_INCORRECT_LOCATION =
            new ReasonToCancel(Reason.incorrectLocation, "");
    public static final ReasonToCancel REASON_DANGEROUS_OR_CAUSE_DAMAGE =
            new ReasonToCancel(Reason.dangerousOrCauseDamage, "Dangerous");


    public final Reason reasonToCancel;
    public final String cancelComment;

    public ReasonToCancel(Reason reasonToCancel, String cancelComment) {
        super();
        this.reasonToCancel = reasonToCancel;
        this.cancelComment = cancelComment;
    }
}

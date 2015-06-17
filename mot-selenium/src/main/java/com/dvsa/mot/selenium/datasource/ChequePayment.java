package com.dvsa.mot.selenium.datasource;

public class ChequePayment {
    
    public static final ChequePayment VALID_CHEQUE_PAYMENTS =
            new ChequePayment(25, "51.25", "51.25", "0.00");
    public static final ChequePayment EXCESS_CHEQUE_PAYMENTS =
            new ChequePayment(100,"206.00", "205.00", "1.00");

    public final int slots;
    public int getSlots() {
        return slots;
    }
    
    public final String amountOnCheque;
    public String getAmountOnCheque() {
        return amountOnCheque;
    }

    public final String cost;
    public String getCost() {
        return cost;
    }

    public final String refundRequired;
    public String getRefundRequired() {
        return refundRequired;
    }

    public ChequePayment(int slots, String amountOnCheque, String cost, String refundRequired)
    {
        this.slots = slots;
        this.amountOnCheque = amountOnCheque;
        this.cost = cost;
        this.refundRequired = refundRequired;
    }
}

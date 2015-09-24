package uk.gov.dvsa.module;

import uk.gov.dvsa.domain.navigation.PageNavigator;

public class Cpms {

    private PageNavigator pageNavigator;

    public final Reports reports;
    public final PurchaseSlots purchaseSlots;
    public final Adjustments adjustments;

    public Cpms(PageNavigator pageNavigator)
    {
        this.pageNavigator = pageNavigator;
        reports = new Reports(pageNavigator);
        purchaseSlots = new PurchaseSlots(pageNavigator);
        adjustments = new Adjustments(pageNavigator);
    }
}

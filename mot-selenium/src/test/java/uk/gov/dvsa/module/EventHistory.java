package uk.gov.dvsa.module;

import org.joda.time.DateTime;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.ui.pages.events.EventType;
import uk.gov.dvsa.ui.pages.events.EventsHistoryPage;

public final class EventHistory {
    private EventsHistoryPage historyPage;
    private AeDetails aeDetails;

    public void setHistoryPage(EventsHistoryPage historyPage, AeDetails aeDetails) {
        this.historyPage = historyPage;
        this.aeDetails = aeDetails;
    }

    public boolean containsEvent(String eventName) {
        if(historyPage != null){
            return historyPage.getEventNames(aeDetails.getIdAsString()).contains(eventName);
        }

        throw new IllegalStateException("Event History Page is not loaded");
    }

    public void createNewEvent(EventType eventType, DateTime dateTime) {

    }
}

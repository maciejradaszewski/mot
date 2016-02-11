package uk.gov.dvsa.ui.pages.events;

public enum HistoryType {
    AE("ae"),
    SITE("site");

    private String type;

    HistoryType(String type) {
        this.type = type;
    }

    @Override
    public String toString() {
        return type;
    }
}

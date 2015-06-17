package com.dvsa.mot.selenium.framework;

public abstract class MarkDown {

    public static String getTextAsItalic(String text) {
        return "*" + text + "*";
    }

    public static String getTextAsBold(String text) {
        return "**" + text + "**";
    }

    public static String getTextAsBoldItalic(String text) {
        return "***" + text + "***";
    }

    public static String getTextAsLargeHeader(String text) {
        return "#" + text;
    }

    public static String getTextAsMediumHeader(String text) {
        return "##" + text;
    }

    public static String getTextAsSmallHeader(String text) {
        return "###" + text;
    }

    public static String getNewLine() {
        return "  \n";
    }

    public static String getNewParagraph() {
        return "\n\n";
    }

    public static String getTextAsLink(String url, String text) {
        return "[" + text + "]" + url;
    }

    // Validation methods
    public static boolean existTextAsItalic(String pageContent, String textThatShouldBeInItalic) {
        return (pageContent != null && pageContent
                .contains("<em>" + textThatShouldBeInItalic + "</em>"));
    }

    public static boolean existTextAsBold(String pageContent, String textThatShouldBeInBold) {
        return (pageContent != null && pageContent
                .contains("<strong>" + textThatShouldBeInBold + "</strong>"));
    }

    public static boolean existTextAsLargeHeader(String pageContent,
            String textThatShouldBeInLargeHeader) {
        return (pageContent != null && pageContent.contains(
                "<h1 id=\"markdowntestline\">" + textThatShouldBeInLargeHeader + "</h1>"));
    }

    public static boolean existTextAsMediumHeader(String pageContent,
            String textThatShouldBeInMediumHeader) {
        return (pageContent != null && pageContent.contains(
                "<h2 id=\"markdowntestline\">" + textThatShouldBeInMediumHeader + "</h2>"));
    }

    public static boolean existTextAsSmallHeader(String pageContent,
            String textThatShouldBeInSmallHeader) {
        return (pageContent != null && pageContent.contains(
                "<h3 id=\"markdowntestline\">" + textThatShouldBeInSmallHeader + "</h3>"));
    }

    public static boolean existTextAsLink(String pageContent, String url, String text) {
        return (pageContent != null && pageContent
                .contains("<a href=\"" + url + "\">" + text + "</a>"));
    }
}

<?php

namespace ProxyProviderBridge\Enum;

enum ProxyIdEnum: string
{
    // Used for scrapping any pages, besides: search engines (they don't allow using this proxy for that)
    case BRIGHT_DATA = "BRIGHT_DATA";

    // Used explicitly for scrapping search engines! Generates high costs, so ONLY for engines scrapping!
    case BRIGHT_DATA_SERP = "BRIGHT_DATA_SERP";
}

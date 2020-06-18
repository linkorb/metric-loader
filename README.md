Metric Loader
=============

CLI tool to extract metrics from various sources into a uniform format.

### Facebook

The facebook metric loader can extract metrics from a Facebook Insights export:

    ./bin/metric-loader facebook /tmp/facebook.csv --prefix=my-page.facebook --keys=daily-total-reach,lifetime-total-likes

This command requires an absolute path to a Facebook Insights export .csv file

Options:

* `--prefix`: prefixes every exported key with this value. Useful if you wish to import insights from multiple facebook pages
* `--keys`: Only extract the listed metric keys (i.e. `daily-total-reach`, `lifetime-total-likes`, etc)

Note that all key names are "Sluggified". So "Daily total reach", becomes "daily-total-reach"


### Matomo

The matomo metric loader can extract metrics from a Matomo export:

    ./bin/metric-loader matomo /tmp/matomo.csv --prefix=my-site.matomo --keys=pageviews,actions

This command requires an absolute path to a Matomo export .csv file

Options:

* `--prefix`: prefixes every exported key with this value. Useful if you wish to import insights from multiple sites pages
* `--keys`: Only extract the listed metric keys (i.e. `pageviews`, `actions`, etc)

Note that all key names are "Sluggified". So "Distinct websites", becomes "distinct-websites"

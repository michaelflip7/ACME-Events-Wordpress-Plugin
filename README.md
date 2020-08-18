#
# **ACME Events Plugin – Documentation**

v1.0 – Last edited on 8/18/20

# **Introduction**

The ACME Events plugin for WordPress uses the ACME Events API and is supported up to version 11.7.0 (August 11th 2020) + WordPress 5.5 (August 11th 2020).

The plugin uses 2 API endpoints, for both filtered and unfiltered event returns:

| /v2/b2b/event/instances/summaries | POST | **Filtered events based on custom post types** |
| --- | --- | --- |
| /v2/b2b/event/instances | GET | **General events** |

# **Plugin Settings**

After installing the plugin, a new menu item will be added within the WordPress administrator dashboard

![](RackMultipart20200818-4-5ygg6m_html_d8bd9256122e96bb.png)

Within this section, you&#39;ll find 3 parameters to customize the ACME events plugin to your environment variables provided by ACME support.

![](RackMultipart20200818-4-5ygg6m_html_98e66ad10ef6a428.png)

Enter your ACME details in the 3 fields and click **Save Changes** to save your data.

# **Usage**

ACME events are called by use of **WordPress Shortcodes** _(For more information on WordPress Shortcodes:_ [_https://codex.wordpress.org/shortcode_](https://codex.wordpress.org/shortcode)_)_, and come with a variety of filters to display events on your webpage. The basic usage is as follows:

1. [acme\_events]

By default, this will return the next 10 upcoming events in a slider, with no filters or other parameters set. By use of the additional Shortcode filters, we can customize the events returned. See the table below for all the possible filter combinations.

| **Parameter** | **Options/Format** | **Default Value** | **Description** |
| --- | --- | --- | --- |
| **start** | YYYY-MM-DD | Current date | If left blank, will default to current date |
| **end** | YYYY-MM-DD |
 | Cutoff date for events returned |
| **limit** | number | 10 | Number of events to return |
| **search** | string |
 | Single keyword only |
| **display** | slider / grid | slider | Slider for carousel slider, grid for 3-column grid format |
| **sort** | asc / desc | asc | Events sorted by date in ascending or descending order |
| **filter** | on / off | off | ON turns on filtered mode (Note: no prices are returned in filter mode, and event images are returned from the &#39;photo&#39; custom field) |
| **operator** | and / or | and | Used for fine tuning filtering when multiple custom fields are being filtered.
**OR**
 Return events labeled Kids OR Course
**AND** Return events labeled Kids AND Course |
| **audience** | Customfield1 |
 | Enter a field value to return events of that kind. Ex. &quot;kids&quot;, &quot;adults&quot;
 Refer to ACME back office for list of custom field values. |
| **event** | Customfield2 |
 | Enter a field value to return events of that kind. Ex. &quot;course&quot;, &quot;lecture&quot;
 Refer to ACME back office for list of custom field values. |

# **Usage Examples**

**Return all events in July 2020 with a grid display format:**

1. [acme\_events start=&quot;2020-07-01&quot;end=&quot;2020-07-31&quot; display=&quot;grid&quot; sort=&quot;desc&quot;]

**Return the next 5 events with keyword &quot;Raven&quot;:**

1. [acme\_events limit=&quot;5&quot; search=&quot;raven&quot;]

**Return all Kids Lectures for the next 1 year** (Note: filter must be set to on):

1. [acme\_events start=&quot;2020-07-25&quot;end=&quot;2021-07-25&quot; filter=&quot;on&quot; audience=&quot;kids&quot;event=&quot;lecture&quot;]

**Return all events labeled adult OR courses, from today onwards** (This will return courses from all audiences):

1. [acme\_events filter=&quot;on&quot; audience=&quot;adults&quot;event=&quot;course&quot;operator=&quot;or&quot;]

**Return all adult events (regardless of event type):**

1. [acme\_events filter=&quot;on&quot; audience=&quot;adults&quot;]

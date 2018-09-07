I have concerns about pre-existing architecture but for the sake of the exercise I assume there is none.

Also I'm assuming the client already has a brand, logo, and color scheme/style guide.

Questions for the client:

* Do you already have an online presence/platform that needs to be considered?

* Do you have a brand, logo, color scheme/style guide?

* What is your vision of this product? Without going deep into the ins-and outs of your business model,
  how do you see your customers using the site? Walk me through a scenario of a customer buying a
  fruit basket online.

* These are my initial concerns (which are hopefully addressed by the answer to the question above)

Selecting the size of the basket, the fruit used, how it is arranged, and embellishments is
not a complicated requirement assuming the client will be entering the item images, descriptions,
prices, and any other associated meta-data into an admin panel to be presented on the storefront.
The question is, based on the client's vision of the app's (and the business's) future, do we need
to provide a custom solution from scratch?

The real-time-progress feature is another matter entirely and one that certainly warrants a custom
solution. The solution I recommend is building a tracking dashboard with role-based features. This
will best facilitate the separation of the concerns of the client, their customers, and our dev team.

One potential happy path:

* A customer successfully places an order
* The order is saved to a database and is placed in a queue.
* An employee is able to check the queue from the employee dashboard and initiate a build
* The employee manually updates the assembly progress at predefined milestones are reached
* Each progress update is published to a server which in turn publishes updates to the client dashboard
  in real time (websockets) where they can be presented however best serves the client.

#### Cons:

Aside from the additional complexity and resources required, the biggest issue I see here is requiring an
employee (be they the client or a hired worker) to manually update the build in order for the customer
to see 'real-time' progress updates. I can't imagine it will be long before any human would find a way
to satisfy this requirement without stopping in the middle of their work-flow to publish a status update.
This will inevitably lead to invalidation of the entire 'tracking system' and thus an undesirable
user experience. This can be mitigated by making the interface as trivial as possible for the employee
(or by implementing some meaningful incentive)

(Given enough time and an unlimited budget I'm confident we could come up with something better...)

#### Pros:

This approach fulfills the spirit of the requirement and is scalable with the business. As the client hires
more 'basket builders', the update-server could load-balance the order queue (between all currently logged-in
builders, for example). Additionally, the data made available by this approach would be invaluable to growing
the business.

For the sake of brevity I will assume I have a good grasp of what the client wants and can make an
estimate based on the time and effort it would take me to deliver it.

There are versions for which I would quote two weeks and charge $1,000 - $2,500 (based on pre-existing assets).

There are also versions for which I would quote a month and charge anywhere from $5,000 - $10,000 (though the
latter likely involves paying a designer and not seeing my family very much)

Also bear in mind the potential ongoing cost of ops, and any future additional features/adjustments.

### Architectural considerations

###### Ops things:

Who is going to operate the thing?
Hosting provider
Domains
Email provider
Support/customer service integration?
Logging
Payment processing

(the choices made above tend to narrow our options slightly or not slightly)

Data storage -
A real-time database would simplify the implementation of the 'progress-update' server mentioned
above but would likely require a supplimental relational db for stability/long-term scalability. I'm
not sure how competative the basket-arranging market is but Firebase is even an option if time is of the
essence.

Back end -
A microservice architecture feels like more trouble here than it's worth. That said, I would at least prefer to
decouple the 'progress-update' logic from the front end and write it in something light and fast like Go.
Cart management and authentication/authorization could be built right into the front end app or broken off into their
own service(s) as needed.

Front end -
Depends heavily on the client's target platform(s). PHP and JavaScript most likely ;) A responsive, accessable web app
will be sufficient in the short term while leaving the option open to leverage a mobile-specific component framework
if the client ever needs something more substantial. (not today, Objective C!)

<!-- conclusion? -->
